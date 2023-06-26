<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\models;

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\permissions\CreateQuestion;
use humhub\modules\content\components\ContentActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * Question Answer model class.
 *
 * The followings are the available columns in table 'question_answer':
 * @property int $id
 * @property int $question_id
 * @property string $answer
 *
 * @property-read Question $question
 *
 * @package humhub.modules.questions.models
 * @author Luke
 */
class QuestionAnswer extends ContentActiveRecord
{

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
        return 'question_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer'], 'required'],
            [['answer'], 'string'],
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
    }

}
