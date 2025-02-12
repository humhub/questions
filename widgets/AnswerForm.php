<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;

class AnswerForm extends Widget
{
    public Question $question;
    public ?QuestionAnswer $answer = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->answer === null) {
            $this->answer = new QuestionAnswer();
            $this->answer->question_id = $this->question;
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return $this->question instanceof Question && parent::beforeRun();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('answer-form', [
            'answer' => $this->answer,
            'options' => $this->getOptions(),
        ]);
    }

    public function getOptions()
    {
        $options = [
            'class' => 'questions-answer-form',
            'data-answer-form' => $this->answer->isNewRecord ? 0 : $this->answer->id,
        ];

        return $options;
    }
}
