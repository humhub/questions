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
     * @var int Count of answers
     */
    public int $count = 0;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->renderCollapseButton() . $this->renderExpandButton();
    }

    private function renderCollapseButton(): string
    {
        $button = Button::info(Yii::t('QuestionsModule.base', 'Collapse all answers ({count})', [
                'count' => '<span class="questions-answers-count">' . $this->count . '</span>'
            ]))
            ->icon('arrow-up')
            ->action('collapse')
            ->sm()->cssClass('active questions-toggle-btn')
            ->loader(false);

        if (!$this->isVisibleButton('collapse')) {
            $button->style('display:none');
        }

        return $button;
    }

    private function renderExpandButton(): string
    {
        $button = Button::info(Yii::t('QuestionsModule.base', 'Expand all answers ({count})', [
                'count' => '<span class="questions-answers-count">' . $this->count . '</span>'
            ]))
            ->icon('arrow-down')
            ->action('expand')
            ->sm()->cssClass('active questions-toggle-btn')
            ->loader(false);

        if (!$this->isVisibleButton('expand')) {
            $button->style('display:none');
        }

        return $button;
    }

    private function isVisibleButton(string $type): bool
    {
        $hideButton = $this->hideButton ?? ($this->count ? 'expand' : 'all');
        return $hideButton !== $type && $hideButton !== 'all';
    }
}
