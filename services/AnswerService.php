<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\services;

use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\permissions\SelectBestAnswer;
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

    private function getUser(?User $user = null): ?User
    {
        if ($user === null && !Yii::$app->user->isGuest) {
            $user = Yii::$app->user->getIdentity();
        }

        return $user;
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
     * @param int|null $limit
     * @return QuestionAnswer[]
     */
    public function getExceptBest(?int $limit = null): array
    {
        return $this->getQuery()->andWhere(['is_best' => 0])->limit($limit)->all();
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
        $user = $this->getUser($user);

        if ($user === null) {
            return false;
        }

        // Question's author always can select the best answer
        if ($this->question->content->createdBy->is($user)) {
            return true;
        }

        return $this->question->content->container->getPermissionManager($user)
            ->can(SelectBestAnswer::class);
    }

    /**
     * Change the "best" flag of the Answer,
     * On set the Answer as the best, previous best answer is unselected automatically,
     * if the Answer was the best before run this action then it will be unselected.
     *
     * @param QuestionAnswer $answer
     * @return bool
     */
    public function changeBest(QuestionAnswer $answer): bool
    {
        /* @var QuestionAnswer[] $bestAnswers */
        $bestAnswers = $this->getQuery()->andWhere(['is_best' => 1])->all();

        // Reset all previous best answers, because only single Answer can be the best selected
        foreach ($bestAnswers as $bestAnswer) {
            $bestAnswer->updateAttributes(['is_best' => 0]);
        }

        return $answer->is_best
            ? true // The Answer has been unselected above, no need to update it twice
            : $answer->updateAttributes(['is_best' => 1]); // Select the Answer as the best
    }

    public function canVote(?User $user = null): bool
    {
        return $this->getUser($user) instanceof User;
    }
}
