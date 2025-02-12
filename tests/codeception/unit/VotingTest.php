<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions;

use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\models\QuestionAnswerVote;

class VotingTest extends UnitTester
{
    public function testVoting()
    {
        $this->becomeUser('Admin');

        $question = $this->createQuestion('Question?', 'Description.');
        $answerService = $question->getAnswerService();

        $answer1 = $this->createAnswer('Answer 1', $question);
        $voteService = $answer1->getVoteService();
        $this->assertTrue($answerService->canVote());
        $this->assertFalse($voteService->canVote());

        $this->becomeUser('User2');
        $this->assertTrue($voteService->canVote());
        $this->assertEquals(0, $voteService->getSummary());
        $this->assertNull($voteService->getVote());
        $this->assertTrue($voteService->vote('up'));
        $this->assertEquals(1, $voteService->getSummary());
        $vote = $voteService->getVote();
        $this->assertEquals(QuestionAnswerVote::TYPE_UP, $vote->type);
        $this->assertTrue($voteService->isVotedWithType('up'));

        $answer2 = $this->createAnswer('Answer 2', $question);
        $voteService = $answer2->getVoteService();

        $this->becomeUser('Admin');
        $this->assertTrue($voteService->canVote());
        $this->assertTrue($voteService->vote('down'));
        $this->assertEquals(-1, $voteService->getSummary());

        $this->becomeUser('User3');
        $this->assertTrue($voteService->canVote());
        $this->assertTrue($voteService->vote('down'));
        $this->assertEquals(-2, $voteService->getSummary());
        $this->assertTrue($voteService->isVotedWithType('down'));
    }

    public function testCheckVotingService()
    {
        $answer = new QuestionAnswer();
        $voteService = $answer->getVoteService();

        $this->assertEquals(QuestionAnswerVote::TYPE_UP, $voteService->normalizeVoteType('up'));
        $this->assertEquals(QuestionAnswerVote::TYPE_UP, $voteService->normalizeVoteType(1));
        $this->assertEquals(QuestionAnswerVote::TYPE_DOWN, $voteService->normalizeVoteType('down'));
        $this->assertEquals(QuestionAnswerVote::TYPE_DOWN, $voteService->normalizeVoteType(-1));
        $this->assertNull($voteService->normalizeVoteType('left'));
    }
}
