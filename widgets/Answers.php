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

    public ?Question $question;

    public bool $isDetailView = false;

    public ?int $currentAnswerId = null;

    private ?ContainerSettings $settings = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->settings = new ContainerSettings(['contentContainer' => $this->question->content->container]);
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        if (!($this->question instanceof Question)) {
            return false;
        }

        if (parent::beforeRun() && $this->isVisible()) {
            $this->content = $this->render('answers', [
                'question' => $this->question,
                'currentAnswerId' => $this->currentAnswerId,
                'bestAnswer' => $bestAnswer = $this->question->getAnswerService()->getBest(),
                'limit' => $this->getLimit($bestAnswer),
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

    protected function isVisible(): bool
    {
        // Always visible on single question view OR when it is enabled in the Container Settings
        return $this->isDetailView || $this->settings->showAnswersInStream;
    }

    protected function getLimit(?QuestionAnswer $bestAnswer): ?int
    {
        if ($this->isDetailView) {
            // Don't limit on single question view
            return null;
        }

        if ($bestAnswer instanceof QuestionAnswer) {
            // Don't display other answers if the best answers exists
            return 0;
        }

        return $this->settings->showAnswersInStream ? 2 : 0;
    }
}
