<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions\acceptance;

use questions\AcceptanceTester;

class QuestionCest
{

    public function testCreateQuestionWithAnswersAndTestVoting(AcceptanceTester $I)
    {
        $I->wantTo('create a Question from stream');
        $I->amAdmin();

        $I->amGoingTo('install the questions module for space 1');
        $I->enableModule(1, 'questions');
        $I->amOnSpace1();

        $I->createQuestion('Question headline text?', 'Question description text.');

        $I->amGoingTo('provide first Answer from Admin');
        $I->click('Provide an answer');
        $I->switchToNextTab();
        $I->provideAnswer('First answer text from Admin.');
        $I->cannotVote(1);

        $I->amUser2(true);
        $I->amOnSpace1();

        $I->amGoingTo('provide first Answer from Sara');
        $I->waitForText('Provide an answer', null, '.wall-entry');
        $I->click('Provide an answer');
        $I->switchToNextTab();
        $I->provideAnswer('Second answer text from Sara.');
        $I->cannotVote(2);

        $I->amGoingTo('vote on the first Answer of Admin');
        $I->upVote(1);
        $I->checkVotingSummary(1, 1);
        $I->downVote(1);
        $I->checkVotingSummary(1, -1);

        $I->amAdmin(true);
        $I->amOnSpace1();

        $I->waitForText('View all answers (2)', null, '.wall-entry');
        $I->click('View all answers (2)');
        $I->switchToNextTab();

        $I->amGoingTo('vote on the second Answer by Admin');
        $I->upVote(2);
        $I->checkVotingSummary(2, 1);
        $I->wait(1);
        $I->upVote(2);
        $I->checkVotingSummary(2, 0);

        $I->amGoingTo('select the best Answer');
        $I->selectBestAnswer(2);
        $I->unselectBestAnswer();
        $I->waitForText('Collapse all answers (2)');
        $I->dontSeeElement('.questions-best-answer');
        $I->selectBestAnswer(1);
        $I->selectBestAnswer(2);
    }

}
