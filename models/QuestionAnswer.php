<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\models;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\widgets\richtext\RichText;
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
            'answer' => Yii::t('QuestionsModule.base', 'Answer')
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

    public function getVoteService(): VoteService
    {
        return new VoteService($this);
    }

}
