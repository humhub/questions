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

    public function testCreateQuestion(AcceptanceTester $I)
    {
        $I->wantTo('create a Question from stream');
        $I->amAdmin();

        $I->amGoingTo('install the questions module for space 1');
        $I->enableModule(1, 'questions');
        $I->amOnSpace1();

        $I->amGoingTo('create a Question');
        $I->waitForText('Q&A');
        $I->click( 'Q&A', '#contentFormMenu');
        $I->waitForElementVisible('input[name="Question[question]"]');
        $I->fillField('Question[question]', 'Question headline text?');
        $I->fillField('#question-description .humhub-ui-richtext', 'Question description text.');
        $I->jsClick('#post_submit_button');

        $I->waitForText('Question headline text?', null, '.wall-entry-header');
        $I->see('Provide an answer', '[data-content-component="questions.Question"]');
        $I->see('View all answers (0)', '[data-content-component="questions.Question"]');

        $I->amGoingTo('provide an Answer');
        $I->click('Provide an answer');
        $I->switchToNextTab();
        $I->waitForText('Collapse all answers (0)');
        $I->fillField('#answerRichTextField0 .humhub-ui-richtext', 'Answer text.');
        $I->click('Save', '.questions-answer-form');
        $I->waitForText('Answer text.', null, '.except-best-answers');
    }

}
