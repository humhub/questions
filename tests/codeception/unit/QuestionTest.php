<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions;

class QuestionTest extends UnitTester
{
    public function testCreateQuestion()
    {
        $this->becomeUser('Admin');

        $question = $this->createQuestion('Sample question title?', 'Sample question description.');

        $this->assertFalse($question->isNewRecord);
        $answerService = $question->getAnswerService();

        $this->assertTrue($question->canAnswer());
        $this->assertTrue($answerService->canSelectBest());

        $this->becomeUser('User2');
        $this->assertTrue($question->canAnswer());
        $this->assertFalse($answerService->canSelectBest());
    }
}
