<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\services;

use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\user\models\User;
use Yii;
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
            ->orderBy(['votes_summary' => SORT_DESC, 'question_answer.id' => SORT_DESC]);
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

    public function canSelectBest(?User $user = null): bool
    {
        // TODO: Implement permission "User can select best answer (Q&A)"
        return false;
    }

    public function canVote(?User $user = null): bool
    {
        if ($user === null && !Yii::$app->user->isGuest) {
            $user = Yii::$app->user->getIdentity();
        }

        return $user instanceof User;
    }
}
