<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/* @var QuestionAnswer $answer */
/* @var array $options */

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\AnswerControls;
use humhub\modules\questions\widgets\AnswerVoting;
use humhub\modules\questions\widgets\BestAnswerButton;
use humhub\modules\user\widgets\Image;
use humhub\widgets\TimeAgo;

?>
<?= Html::beginTag('div', $options) ?>

    <?= AnswerVoting::widget(['answer' => $answer]) ?>

    <div class="questions-answer-content">
        <div class="questions-answer-header">
            <?= Image::widget([
                'user' => $answer->createdBy,
                'width' => 29
            ]) ?>
            <strong><?= $answer->createdBy->displayName ?></strong>
            &middot;
            <small><?= Yii::t('QuestionsModule.base', 'answered {date}', [
                'date' => TimeAgo::widget(['timestamp' => $answer->created_at])
            ]) ?></small>
            <?= AnswerControls::widget(['answer' => $answer]) ?>
        </div>
        <?= RichText::output($answer->answer) ?>
        <?= BestAnswerButton::widget(['answer' => $answer]) ?>
    </div>

<?= Html::endTag('div') ?>
