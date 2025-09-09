<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\models;

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\notifications\NewQuestionNotification;
use humhub\modules\questions\permissions\CreateQuestion;
use humhub\modules\questions\services\AnswerService;
use humhub\modules\questions\widgets\WallEntry;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\space\models\Membership;
use humhub\modules\space\models\Space;
use humhub\modules\user\components\ActiveQueryUser;
use humhub\modules\user\models\User;
use Yii;

/**
 * Question model class.
 *
 * The followings are the available columns in table 'question':
 * @property int $id
 * @property string $question
 * @property string $description
 *
 * @package humhub.modules.questions.models
 * @author Luke
 */
class Question extends ContentActiveRecord implements Searchable
{
    /**
     * @inheritdoc
     */
    public $wallEntryClass = WallEntry::class;

    /**
     * @inheritdoc
     */
    protected $createPermission = CreateQuestion::class;

    /**
     * @inheritdoc
     */
    public $moduleId = 'questions';

    /**
     * @inheritdoc
     */
    protected $canMove = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function getIcon()
    {
        return 'fa-question-circle';
    }

    /**
     * @inheritdoc
     */
    public function getContentName()
    {
        return Yii::t('QuestionsModule.base', 'Q&A');
    }

    /**
     * @inheritdoc
     */
    public function getContentDescription()
    {
        return $this->question;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question'], 'string', 'max' => 255],
            [['question'], 'required'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question' => Yii::t('QuestionsModule.base', 'Question'),
            'description' => Yii::t('QuestionsModule.base', 'Description'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        RichText::postProcess($this->description, $this);
        $this->sendNotification($insert);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach ($this->getAnswerService()->getAll() as $answer) {
            $answer->delete();
        }

        return true;
    }

    public function getSearchAttributes()
    {
        $itemAnswers = '';

        foreach ($this->getAnswerService()->getAll() as $answer) {
            $itemAnswers .= $answer->answer . ' ';
        }

        return [
            'question' => $this->question,
            'description' => $this->description,
            'itemAnswers' => trim($itemAnswers),
        ];
    }

    public function getAnswerService(): AnswerService
    {
        return new AnswerService($this);
    }

    public function canAnswer($user = null): bool
    {
        return (new QuestionAnswer(['question_id' => $this->id]))->canEdit($user);
    }

    public function getUrl(): string
    {
        return Url::toViewQuestion($this);
    }

    public function sendNotification(bool $isNewRecord = false)
    {
        if ($isNewRecord) {
            Yii::createObject(['class' => NewQuestionNotification::class])
                ->from(Yii::$app->user->getIdentity())
                ->about($this)
                ->sendBulk($this->getNotificationUserQuery());
        }
    }

    public function getNotificationUserQuery(): ActiveQueryUser
    {
        if ($this->content->container instanceof Space) {
            return Membership::getSpaceMembersQuery($this->content->container);
        }

        // Fallback should only happen for global events, which are not supported
        return User::find()->where(['id' => $this->content->created_by]);
    }

}
