<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\modules\questions\models\forms\ContainerSettings;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\widgets\JsWidget;

class Answers extends JsWidget
{
    /**
     * @inheritdoc
     */
    public $jsWidget = 'questions.Answer';

    /**
     * @inheritdoc
     */
    public $init = true;

    public ?Question $question = null;

    public bool $isDetailView = false;

    public ?int $currentAnswerId = null;

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        if (!($this->question instanceof Question)) {
            return false;
        }

        if (parent::beforeRun()) {
            $this->content = $this->render('answers', [
                'question' => $this->question,
                'currentAnswerId' => $this->currentAnswerId,
                'bestAnswer' => $bestAnswer = $this->question->getAnswerService()->getBest(),
                'otherAnswersLimit' => $this->getOtherAnswersLimit($bestAnswer),
                'isDetailView' => $this->isDetailView,
            ]);
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributes()
    {
        return ['class' => 'questions-answers'];
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        return ['question' => $this->question->id];
    }

    protected function getOtherAnswersLimit(?QuestionAnswer $bestAnswer): ?int
    {
        if ($this->isDetailView) {
            // Don't limit on single question view
            return null;
        }

        if ($bestAnswer instanceof QuestionAnswer) {
            // Don't display other answers if the best answers exists
            return 0;
        }

        $settings = new ContainerSettings(['contentContainer' => $this->question->content->container]);
        return $settings->showAnswersInStream ? 2 : 0;
    }
}
