<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\widgets\Label;
use Yii;

class BestAnswerButton extends Widget
{
    public ?QuestionAnswer $answer;

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
        $button = Label::info(Yii::t('QuestionsModule.base', 'BEST ANSWER'))
            ->cssClass('questions-best-answer-button');

        if ($this->answer->question->getAnswerService()->canSelectBest()) {
            $button->action('best', Url::toSelectBestAnswer($this->answer))
                ->tooltip($this->answer->is_best
                    ? Yii::t('QuestionsModule.base', 'Unselect best answer')
                    : Yii::t('QuestionsModule.base', 'Select best answer'));
        }

        return $button;
    }
}
