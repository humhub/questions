<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\models;

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\permissions\CreateQuestion;
use humhub\modules\questions\widgets\WallEntry;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\content\components\ContentActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * Question model class.
 *
 * The followings are the available columns in table 'question':
 * @property integer $id
 * @property string $question
 * @property string $description
 *
 * @property-read QuestionAnswer[] $answers
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
        return Yii::t('QuestionsModule.base', 'Question');
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

    public function getAnswers(): ActiveQuery
    {
        return $this->hasMany(QuestionAnswer::class, ['question_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        RichText::postProcess($this->description, $this);
    }

    public function getSearchAttributes()
    {
        $itemAnswers = '';

        foreach ($this->answers as $answer) {
            $itemAnswers .= $answer->answer . ' ';
        }

        return [
            'question' => $this->question,
            'description' => $this->description,
            'itemAnswers' => trim($itemAnswers)
        ];
    }

}
