<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\Menu;
use Yii;

class AnswerControls extends Menu
{
    public QuestionAnswer $answer;

    /**
     * @inheritdoc
     */
    public $template = 'answer-controls';

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return $this->answer instanceof QuestionAnswer &&
            !$this->answer->isNewRecord &&
            parent::beforeRun();
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initAnswerControls();
        parent::init();
    }

    private function initAnswerControls()
    {
        $this->addEntry(new MenuLink([
            'label' => Yii::t('QuestionsModule.base', 'Permalink'),
            'icon' => 'link',
            'url' => Url::toViewAnswer($this->answer),
            'sortOrder' => 100,
        ]));

        if ($this->answer->canEdit()) {
            $this->addEntry(new MenuLink([
                'label' => Yii::t('QuestionsModule.base', 'Edit'),
                'icon' => 'edit',
                'url' => '#',
                'htmlOptions' => [
                    'data-action-click' => 'edit',
                    'data-action-url' => Url::toEditAnswer($this->answer)
                ],
                'sortOrder' => 200,
            ]));

            $this->addEntry(new MenuLink([
                'label' => Yii::t('QuestionsModule.base', 'Delete'),
                'icon' => 'delete',
                'url' => '#',
                'htmlOptions' => [
                    'data-action-click' => 'delete',
                    'data-action-url' => Url::toDeleteAnswer($this->answer),
                    'data-action-confirm-header' => Yii::t('QuestionsModule.base', '<strong>Delete</strong> Answer'),
                    'data-action-confirm' => Yii::t('QuestionsModule.base', 'Are you sure to delete this Answer?'),
                ],
                'sortOrder' => 300,
            ]));
        }
    }

    /**
     * @inheritdoc
     */
    public function getAttributes()
    {
        return [
            'class' => 'nav nav-pills preferences'
        ];
    }
}
