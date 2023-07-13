<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\widgets\Button;
use Yii;

class AnswersToggleButton extends Widget
{
    /**
     * @var string What button should be hidden by default: 'expand', 'collapse', 'all'
     */
    public string $hideButton = 'expand';

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->renderCollapseButton() . $this->renderExpandButton();
    }

    private function renderCollapseButton(): string
    {
        $button = Button::info(Yii::t('QuestionsModule.base', 'Collapse all answers'))
            ->icon('arrow-up')
            ->action('collapse')
            ->sm()->cssClass('active')
            ->loader(false);

        if ($this->hideButton === 'collapse' || $this->hideButton === 'all') {
            $button->style('display:none');
        }

        return $button;
    }

    private function renderExpandButton(): string
    {
        $button = Button::info(Yii::t('QuestionsModule.base', 'Expand all answers'))
            ->icon('arrow-down')
            ->action('expand')
            ->sm()->cssClass('active')
            ->loader(false);

        if ($this->hideButton === 'expand' || $this->hideButton === 'all') {
            $button->style('display:none');
        }

        return $button;
    }
}
