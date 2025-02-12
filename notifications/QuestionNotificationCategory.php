<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\notifications;

use humhub\modules\notification\components\NotificationCategory;
use Yii;

/**
 * Class QuestionNotificationCategory to notify about new created Question
 */
class QuestionNotificationCategory extends NotificationCategory
{
    /**
     * @inheritdoc
     */
    public $id = 'questions';

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('QuestionsModule.base', 'Q&A');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('QuestionsModule.base', 'Receive notifications when a Question is created or your questions are answered.');
    }
}
