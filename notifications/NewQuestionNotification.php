<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\notifications;

use humhub\modules\notification\components\MessageParam;
use humhub\modules\questions\models\Question;
use Yii;

/**
 * Notifies about a new created Question
 */
final class NewQuestionNotification extends AbstractQuestionNotification
{
    protected function getMessage(array $params): string
    {
        if (isset($params['spaceName'])) {
            return Yii::t('QuestionsModule.base', '{displayName} has created the Question "{contentTitle}" in Space {spaceName}.', $params);
        }

        return Yii::t('QuestionsModule.base', '{displayName} has created the Question "{contentTitle}".', $params);
    }

    protected function getMessageParams(): array
    {
        $question = $this->getSubjectRecord();

        return [
            'questionTitle' => MessageParam::text($question instanceof Question ? (string) $question->question : ''),
        ];
    }

    protected function getMailSubject(array $params): string
    {
        return Yii::t('QuestionsModule.base', 'New Question "{questionTitle}"', $params);
    }
}
