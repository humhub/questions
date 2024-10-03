<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\models;

use humhub\components\ActiveRecord;
use humhub\modules\content\permissions\ManageContent;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\notifications\AnsweredNotification;
use humhub\modules\questions\permissions\CreateQuestion;
use humhub\modules\questions\services\VoteService;
use Yii;
use yii\db\ActiveQuery;

/**
 * Question Answer model class.
 *
 * The followings are the available columns in table 'question_answer':
 * @property int $id
 * @property int $question_id
 * @property string $answer
 * @property int $votes_summary
 * @property bool $is_best
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property-read Question $question
 *
 * @package humhub.modules.questions.models
 * @author Luke
 */
class QuestionAnswer extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'answer'], 'required'],
            [['question_id'], 'integer'],
            [['answer'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'answer' => Yii::t('QuestionsModule.base', 'Provide an answer'),
        ];
    }

    public function getQuestion(): ActiveQuery
    {
        return $this->hasOne(Question::class, ['id' => 'question_id']);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        RichText::postProcess($this->answer, $this);
        $this->sendNotification($insert);
    }

    public function getVoteService(): VoteService
    {
        return new VoteService($this);
    }

    public function canEdit($user = null): bool
    {
        if ($user === null) {
            if (Yii::$app->user->isGuest) {
                return false;
            }
            $user = Yii::$app->user->getIdentity();
        }

        // Author can always edit own Answer
        if ($this->created_by == $user->id) {
            return true;
        }

        // Global Admin can edit arbitrarily content
        if (Yii::$app->getModule('content')->adminCanEditAllContent && $user->isSystemAdmin()) {
            return true;
        }

        return $this->question->content->container->getPermissionManager($user)
            ->can($this->isNewRecord ? CreateQuestion::class : ManageContent::class);
    }

    public function sendNotification(bool $isNewRecord = false)
    {
        if ($isNewRecord) {
            Yii::createObject(['class' => AnsweredNotification::class])
                ->from(Yii::$app->user->getIdentity())
                ->about($this)
                ->sendBulk($this->question->content->getCreatedBy());
        }
    }

}
