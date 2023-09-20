<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\notifications;

use humhub\libs\Html;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\questions\models\Question;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;

/**
 * Class NewQuestionNotification to notify about new created Question
 */
class NewQuestionNotification extends BaseNotification
{
    /**
     * @inheritdoc
     */
    public $moduleId = 'questions';

    /**
     * @inheritdoc
     * @var Question
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
        if ($this->source->content->container instanceof Space) {
            return Yii::t('QuestionsModule.base', '{displayName} has created the Question "{contentTitle}" in Space {spaceName}.', [
                'displayName' => Html::tag('strong', Html::encode($this->originator->displayName)),
                'contentTitle' => $this->getContentInfo($this->source, false),
                'spaceName' => Html::encode($this->source->content->container->displayName)
            ]);
        }

        return Yii::t('QuestionsModule.base', '{displayName} has created the Question "{contentTitle}".', [
            'displayName' => Html::tag('strong', Html::encode($this->originator->displayName)),
            'contentTitle' => $this->getContentInfo($this->source, false)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getMailSubject()
    {
        return Yii::t('QuestionsModule.base', 'New question "{questionTitle}"', [
            'questionTitle' => $this->source->question
        ]);
    }

    /**
     * @inheritdoc
     */
    public function isBlockedForUser(User $user): bool
    {
        return !$this->source->content->canView($user) &&
            parent::isBlockedForUser($user);
    }
}
