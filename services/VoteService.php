<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\services;

use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\models\QuestionAnswerVote;
use humhub\modules\user\models\User;
use Yii;
use yii\db\ActiveQuery;

class VoteService
{
    public QuestionAnswer $answer;

    public function __construct(QuestionAnswer $answer)
    {
        $this->answer = $answer;
    }

    private function getUser(?User $user = null): ?User
    {
        if ($user === null && !Yii::$app->user->isGuest) {
            $user = Yii::$app->user->getIdentity();
        }

        return $user;
    }

    public function canVote(?User $user = null): bool
    {
        $user = $this->getUser($user);

        return $user instanceof User && $user->id !== $this->answer->created_by;
    }

    public function getVote(?User $user = null): ?QuestionAnswerVote
    {
        $user = $this->getUser($user);
        if ($user === null) {
            return null;
        }

        return QuestionAnswerVote::findOne([
            'answer_id' => $this->answer->id,
            'user_id' => $user->id,
        ]);
    }

    public function normalizeVoteType($value): ?int
    {
        if ($value === 'up' || $value === QuestionAnswerVote::TYPE_UP) {
            return QuestionAnswerVote::TYPE_UP;
        }

        if ($value === 'down' || $value === QuestionAnswerVote::TYPE_DOWN) {
            return QuestionAnswerVote::TYPE_DOWN;
        }

        return null;
    }

    public function vote($voteType, ?User $user = null): bool
    {
        $voteType = $this->normalizeVoteType($voteType);

        if ($voteType === null) {
            return false;
        }

        if (!$this->canVote($user)) {
            return false;
        }

        $user = $this->getUser($user);
        $vote = $this->getVote($user);

        // New vote
        if ($vote === null) {
            $vote = new QuestionAnswerVote();
            $vote->answer_id = $this->answer->id;
            $vote->user_id = $user->id;
            $vote->type = $voteType;
            return $vote->save();
        }

        // Remove a vote if it is voted with same type
        if ($vote->is($voteType)) {
            return (bool) $vote->delete();
        }

        // Change a vote to another type
        $vote->type = $voteType;
        return $vote->save();
    }

    public function isVotedWithType($type, ?User $user = null): bool
    {
        $vote = $this->getVote($user);

        return $vote instanceof QuestionAnswerVote
            && $vote->is($this->normalizeVoteType($type));
    }

    public function getQuery(): ActiveQuery
    {
        return $this->answer->hasMany(QuestionAnswerVote::class, ['answer_id' => 'id']);
    }

    public function getSummary(): int
    {
        return (int) $this->getQuery()->sum('type');
    }

    public function refreshSummary()
    {
        $this->answer->updateAttributes([
            'votes_summary' => $this->getSummary(),
        ]);
    }
}
