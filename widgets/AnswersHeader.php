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
        $count = $this->question->getAnswerService()->getExceptBestQuery()->count();

        return Html::tag('h4', Yii::t('QuestionsModule.base', '{countAnswers} more {n,plural,=1{Answer} other{Answers}}', [
            'countAnswers' => $count, 'n' => $count
        ]), [
            'class' => 'except-best-answers-header'
        ]);
    }
}
