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
    public ?QuestionAnswer $answer;

    public bool $highlight = false;
    public bool $allowSelectBest = true;

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
            'allowSelectBest' => $this->allowSelectBest,
            'options' => $this->getOptions()
        ]);
    }

    protected function getOptions(): array
    {
        return [
            'id' => 'answer' . $this->answer->id,
            'class' => $this->getStyleClass(),
            'data-answer' => $this->answer->id
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
}
