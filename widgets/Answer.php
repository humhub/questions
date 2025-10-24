<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\modules\questions\models\QuestionAnswer;

class Answer extends Widget
{
    public ?QuestionAnswer $answer = null;

    public bool $highlight = false;
    public bool $isDetailView = true;

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return $this->answer instanceof QuestionAnswer && parent::beforeRun();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('answer', [
            'answer' => $this->answer,
            'enableControls' => $this->isDetailView,
            'options' => $this->getOptions(),
            'contentAttributes' => $this->getContentAttributes(),
        ]);
    }

    protected function getOptions(): array
    {
        return [
            'class' => $this->getStyleClass(),
            'data-answer' => $this->answer->id,
        ];
    }

    protected function getStyleClass(): string
    {
        $class = 'questions-answer';

        if ($this->answer->is_best) {
            $class .= ' questions-best-answer';
        }

        if ($this->highlight) {
            $class .= ' questions-highlight-answer';
        }

        return $class;
    }

    protected function getContentAttributes(): array
    {
        $attrs = ['data-ui-markdown' => true];

        if (!$this->isDetailView) {
            $attrs['data-ui-show-more'] = true;
        }

        return $attrs;
    }
}
