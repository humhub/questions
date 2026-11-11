<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\notifications;

use humhub\modules\content\interfaces\ContentOwner;
use humhub\modules\notification\components\MessageParam;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\space\models\Space;
use Yii;

/**
 * Notifies the author of a Question that it was answered. The source is the answer, so the
 * notification goes with it when the answer is deleted.
 */
final class AnsweredNotification extends AbstractQuestionNotification
{
    protected function getMessage(array $params): string
    {
        if (isset($params['spaceName'])) {
            return Yii::t('QuestionsModule.base', '{displayName} has answered your Question "{contentTitle}" in Space {spaceName}.', $params);
        }

        return Yii::t('QuestionsModule.base', '{displayName} has answered your Question "{contentTitle}".', $params);
    }

    protected function getMessageParams(): array
    {
        $question = $this->getQuestion();

        return [
            'contentTitle' => $question !== null ? MessageParam::content($question, withType: false) : '',
            'questionTitle' => MessageParam::text((string) $question?->question),
        ];
    }

    protected function getMailSubject(array $params): string
    {
        return Yii::t('QuestionsModule.base', 'New answer to your Question "{questionTitle}"', $params);
    }

    public function getUrl(bool $scheme = false): ?string
    {
        $answer = $this->getAnswer();

        return $answer !== null ? Url::toViewAnswer($answer, $scheme) : null;
    }

    /**
     * The Question the preview under the sentence is about.
     */
    public function getSubjectRecord(): ?ContentOwner
    {
        return $this->getQuestion();
    }

    /**
     * The Space of the Question, as the answer itself has no container.
     */
    public function getSpace(): ?Space
    {
        $container = $this->getQuestion()?->content->container;

        return $container instanceof Space ? $container : null;
    }

    private function getAnswer(): ?QuestionAnswer
    {
        return $this->sourceRecord instanceof QuestionAnswer ? $this->sourceRecord : null;
    }

    private function getQuestion(): ?Question
    {
        return $this->getAnswer()?->question;
    }
}
