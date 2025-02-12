<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions;

class AnswerTest extends UnitTester
{
    public function testCreateAnswers()
    {
        $this->becomeUser('Admin');

        $question = $this->createQuestion('Question?', 'Description.');
        $answerService = $question->getAnswerService();

        $answer1 = $this->createAnswer('Answer 1', $question);
        $this->assertFalse($answer1->isNewRecord);
        $this->assertTrue($question->canAnswer());
        $this->assertTrue($answerService->canSelectBest());

        $this->becomeUser('User1');
        $this->assertFalse($question->canAnswer());
        $this->assertFalse($answerService->canSelectBest());

        $this->becomeUser('User2');
        $this->assertTrue($question->canAnswer());
        $answer2 = $this->createAnswer('Answer 2', $question);
        $this->assertFalse($answer2->isNewRecord);
        $this->assertFalse($answerService->canSelectBest());
    }

    public function testSelectBestAnswer()
    {
        $this->becomeUser('Admin');

        $question = $this->createQuestion('Question?', 'Description.');
        $answerService = $question->getAnswerService();

        $answer1 = $this->createAnswer('Answer 1', $question);
        $answer2 = $this->createAnswer('Answer 2', $question);
        $answer3 = $this->createAnswer('Answer 3', $question);

        $this->assertEquals(3, $answerService->getCount());
        $this->assertEquals(3, $answerService->getExceptBestQuery()->count());
        $this->assertNull($answerService->getBest());

        $this->assertTrue($answerService->changeBest($answer1));
        $this->assertEquals($answerService->getBest()->id, $answer1->id);
        $this->assertEquals(2, $answerService->getExceptBestQuery()->count());

        $this->assertTrue($answerService->changeBest($answer3));
        $this->assertEquals($answerService->getBest()->id, $answer3->id);
        $this->assertEquals(2, $answerService->getExceptBestQuery()->count());

        $this->assertTrue($answerService->changeBest($answer3));
        $this->assertNull($answerService->getBest());
        $this->assertEquals(3, $answerService->getExceptBestQuery()->count());
    }
}
