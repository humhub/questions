<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\libs\Html;
use humhub\modules\questions\models\Question;
use Yii;

class AnswersHeader extends Widget
{
    public ?Question $question;

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
        return Html::tag('h4', $this->getText(), [
            'class' => 'except-best-answers-header'
        ]);
    }

    private function getText(): string
    {
        $answerService = $this->question->getAnswerService();
        $count = $answerService->getExceptBestQuery()->count();
        $params = ['countAnswers' => $count, 'n' => $count];

        return $answerService->getBestQuery()->exists()
            ? Yii::t('QuestionsModule.base', '{countAnswers} more {n,plural,=1{answer} other{answers}}', $params)
            : Yii::t('QuestionsModule.base', '{countAnswers} {n,plural,=1{answer} other{answers}}', $params);
    }
}
