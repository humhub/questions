<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\helpers\Html;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\widgets\bootstrap\Label;
use Yii;

/**
 * Widget BestAnswerButton displays a button to select/unselect the Best Answer
 *        if user has a permission, otherwise it is displayed as label only for the Best Answer
 */
class BestAnswerButton extends Widget
{
    public ?QuestionAnswer $answer;

    public bool $allowSelect = true;

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return parent::beforeRun() && $this->isVisible();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $button = Label::info(Yii::t('QuestionsModule.base', 'BEST ANSWER'));

        if ($this->allowSelect()) {
            $button->action('best', Url::toSelectBestAnswer($this->answer))
                ->tooltip($this->answer->is_best
                    ? Yii::t('QuestionsModule.base', 'Unselect best answer')
                    : Yii::t('QuestionsModule.base', 'Select best answer'));
        }

        return Html::tag('div', $button, ['class' => 'questions-best-answer-button']);
    }

    private function isVisible(): bool
    {
        if (!($this->answer instanceof QuestionAnswer)) {
            return false;
        }

        return $this->answer->is_best || $this->allowSelect();
    }

    private function allowSelect(): bool
    {
        return $this->allowSelect && $this->answer->question->getAnswerService()->canSelectBest();
    }
}
