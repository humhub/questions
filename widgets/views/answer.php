<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\AnswerControls;
use humhub\modules\questions\widgets\AnswerVoting;
use humhub\modules\questions\widgets\BestAnswerButton;
use humhub\modules\user\widgets\Image;
use humhub\widgets\TimeAgo;

/* @var QuestionAnswer $answer */
/* @var array $options */
/* @var bool $enableControls */
?>
<?= Html::beginTag('div', $options) ?>

    <?php if ($enableControls) : ?>
        <?= AnswerVoting::widget(['answer' => $answer]) ?>
    <?php endif; ?>

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
        <?= BestAnswerButton::widget(['answer' => $answer, 'allowSelect' => $enableControls]) ?>
    </div>

<?= Html::endTag('div') ?>
