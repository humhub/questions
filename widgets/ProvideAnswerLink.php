<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\libs\Html;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;
use Yii;

class ProvideAnswerLink extends Widget
{
    public Question $question;

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return parent::beforeRun() && $this->question->canAnswer();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return Html::a(Yii::t('QuestionsModule.base', 'Provide an answer'), Url::toCreateAnswer($this->question), [
            'target' => '_self'
        ]);
    }
}
