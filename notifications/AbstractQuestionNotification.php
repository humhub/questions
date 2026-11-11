<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\notifications;

use humhub\modules\notification\components\BaseNotification;
use humhub\modules\notification\components\NotificationCategory;
use Yii;

/**
 * Base of the notifications about a Question: the category the users switch them with.
 */
abstract class AbstractQuestionNotification extends BaseNotification
{
    public static function category(): NotificationCategory
    {
        return new NotificationCategory(
            'questions',
            Yii::t('QuestionsModule.base', 'Q&A'),
            Yii::t('QuestionsModule.base', 'Receive notifications when a Question is created or your questions are answered.'),
        );
    }
}
