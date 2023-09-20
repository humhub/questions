<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\notifications;

use humhub\libs\Html;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;

/**
 * Class AnsweredNotification to notify answered to own Questions
 */
class AnsweredNotification extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = 'questions';

    /**
     * @inheritdoc
     * @var QuestionAnswer
     */
    public $source;

    /**
     * @inheritdoc
     */
    public function category()
    {
        return new QuestionNotificationCategory();
    }

    /**
     * @inheritdoc
     */
    public function html()
    {
        if ($this->source->question->content->container instanceof Space) {
            return Yii::t('QuestionsModule.base', '{displayName} has answered to your Question "{contentTitle}" in Space {spaceName}.', [
                'displayName' => Html::tag('strong', Html::encode($this->originator->displayName)),
                'contentTitle' => $this->getContentInfo($this->source->question, false),
                'spaceName' => Html::encode($this->source->question->content->container->displayName)
            ]);
        }

        return Yii::t('QuestionsModule.base', '{displayName} has answered to your Question "{contentTitle}".', [
            'displayName' => Html::tag('strong', Html::encode($this->originator->displayName)),
            'contentTitle' => $this->getContentInfo($this->source->question, false)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getMailSubject()
    {
        return Yii::t('QuestionsModule.base', 'Answered to your question "{questionTitle}"', [
            'questionTitle' => $this->source->question->question
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return Url::toViewAnswer($this->source);
    }

    /**
     * @inheritdoc
     */
    public function isBlockedForUser(User $user): bool
    {
        return !$this->source->question->content->createdBy->is($user) &&
            parent::isBlockedForUser($user);
    }
}
