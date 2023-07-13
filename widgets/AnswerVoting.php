<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\modules\questions\models\QuestionAnswer;

class AnswerVoting extends Widget
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
        return $this->render('answer-voting', [
            'answer' => $this->answer,
            'options' => $this->getOptions()
        ]);
    }

    protected function getOptions(): array
    {
        return ['class' => 'questions-answer-voting'];
    }
}
