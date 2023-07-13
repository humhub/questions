<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\modules\questions\models\Question;
use humhub\widgets\JsWidget;

class Answers extends JsWidget
{
    /**
     * @inheritdoc
     */
    public $jsWidget = 'questions.Answer';

    public ?Question $question;

    public bool $displayAll = false;

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
                'bestAnswer' => $this->question->getAnswerService()->getBest(),
                'displayAll' => $this->displayAll
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
}
