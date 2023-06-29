<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\models;

use humhub\components\ActiveRecord;
use humhub\modules\user\models\User;
use yii\db\ActiveQuery;

/**
 * Question Answer Vote model class.
 *
 * The followings are the available columns in table 'question_answer_vote':
 * @property int $answer_id
 * @property int $user_id
 * @property int $type
 *
 * @property-read QuestionAnswer $answer
 * @property-read User $user
 *
 * @package humhub.modules.questions.models
 * @author Luke
 */
class QuestionAnswerVote extends ActiveRecord
{
    const TYPE_UP = 1;
    const TYPE_DOWN = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_answer_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['answer_id', 'user_id', 'type'], 'required'],
            [['answer_id', 'user_id', 'type'], 'integer'],
        ];
    }

    public function getAnswer(): ActiveQuery
    {
        return $this->hasOne(QuestionAnswer::class, ['id' => 'answer_id']);
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->answer->getVoteService()->refreshSummary();
    }

}
