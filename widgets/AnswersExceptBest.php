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
        $answers = $this->question->getAnswerService()->getExceptBest($this->limit);

        if (count($answers) === 0) {
            return '';
        }

        return $this->render('answersExceptBest', [
            'answers' => $answers,
            'count' => $this->question->getAnswerService()->getCount(),
        ]);
    }
}
