<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\modules\questions\models\Question;

class AnswersExceptBest extends Widget
{
    public ?Question $question;

    public ?int $limit = null;

    public ?int $currentAnswerId = null;

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
        return $this->render('answers-except-best', [
            'question' => $this->question,
            'currentAnswerId' => $this->currentAnswerId,
            'answers' => $this->question->getAnswerService()->getExceptBest($this->limit),
            'options' => $this->getOptions()
        ]);
    }

    public function getOptions(): array
    {
        return [
            'id' => 'answers',
            'class' => 'except-best-answers'
        ];
    }
}
