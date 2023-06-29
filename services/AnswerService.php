<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\services;

use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use yii\db\ActiveQuery;

class AnswerService
{
    public Question $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }

    public function getQuery(): ActiveQuery
    {
        return $this->question->hasMany(QuestionAnswer::class, ['question_id' => 'id'])
            ->orderBy(['votes_count' => SORT_DESC, 'question_answer.id' => SORT_DESC]);
    }

    /**
     * @return QuestionAnswer[]
     */
    public function getAll(): array
    {
        return $this->getQuery()->all();
    }

    /**
     * @return QuestionAnswer[]
     */
    public function getExceptBest(): array
    {
        return $this->getQuery()->andWhere(['is_best' => 0])->all();
    }

    public function getBest(): ?QuestionAnswer
    {
        return $this->getQuery()->andWhere(['is_best' => 1])->one();
    }

    public function getCount(): int
    {
        return $this->getQuery()->count();
    }
}
