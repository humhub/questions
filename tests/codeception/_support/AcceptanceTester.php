<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\questions\models\Question;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \AcceptanceTester
{
    use _generated\AcceptanceTesterActions;

    public function createQuestion(string $headline, string $description)
    {
        $this->amGoingTo('create a Question');
        $this->waitForText('Q&A');
        $this->click('Q&A', '#contentFormMenu');
        $this->waitForElementVisible('input[name="Question[question]"]');
        $this->fillField('Question[question]', $headline);
        $this->fillField('#question-description .humhub-ui-richtext', $description);
        $this->jsClick('#post_submit_button');

        $this->waitForText($headline, null, '.wall-entry-header');
        $this->see('Provide an answer', '[data-content-component="questions.Question"]');
        $this->see('View all answers', '[data-content-component="questions.Question"]');
    }

    public function provideAnswer(string $answerText)
    {
        $this->waitForText('Provide an answer', null, '.field-questionanswer-answer');
        $this->fillField('#answerRichTextField0 .humhub-ui-richtext', $answerText);
        $this->click('Save', '.questions-answer-form');
        $this->waitForText($answerText, null, '.except-best-answers');
    }

    private function getVoteButtonSelector(int $answerId, string $suffix = ''): string
    {
        return '[data-answer="' . $answerId . '"] [data-action-click="vote"]' . $suffix;
    }

    public function canVote(int $answerId)
    {
        $this->waitForElementVisible($this->getVoteButtonSelector($answerId, ':not([disabled])'));
    }

    public function cannotVote(int $answerId)
    {
        $this->waitForElementVisible($this->getVoteButtonSelector($answerId, '[disabled]'));
    }

    public function vote(int $answerId, string $buttonTitle)
    {
        $this->canVote($answerId);
        $buttonSelector = $this->getVoteButtonSelector($answerId, '[data-original-title="' . $buttonTitle . '"]');
        $this->waitForElementVisible($buttonSelector);
        $this->jsClick($buttonSelector);
        $this->waitForElementVisible($buttonSelector . '.active');
    }

    public function upVote(int $answerId)
    {
        $this->vote($answerId, 'Upvote');
    }

    public function downVote(int $answerId)
    {
        $this->vote($answerId, 'Downvote');
    }

    public function checkVotingSummary(int $answerId, int $summary)
    {
        $this->waitForText((string)$summary, null, '[data-answer="' . $answerId . '"] .questions-answer-voting div');
    }

    public function selectBestAnswer(int $answerId)
    {
        $answerSelector = '[data-answer="' . $answerId . '"]';
        $this->moveMouseOver(['css' => $answerSelector]);
        $bestButtonSelector = '[data-action-click="best"]';
        $this->waitForElementVisible($answerSelector . ' ' . $bestButtonSelector);
        $this->jsClick($answerSelector . ' ' . $bestButtonSelector);
        $this->waitForElementVisible($answerSelector . '.questions-best-answer');
    }

    public function unselectBestAnswer()
    {
        $this->waitForElementVisible('.questions-best-answer');
        $bestButtonSelector = '[data-action-click="best"]';
        $this->waitForElementVisible($bestButtonSelector);
        $this->jsClick('.questions-best-answer ' . $bestButtonSelector);
    }
}
