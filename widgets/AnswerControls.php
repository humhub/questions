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
