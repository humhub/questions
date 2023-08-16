<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\widgets\Button;

/* @var QuestionAnswer $answer */
/* @var array $options */
?>
<?= Html::beginTag('div', $options) ?>

    <?php $form = ActiveForm::begin(['action' => Url::toEditAnswer($answer)]) ?>

    <?= $form->field($answer, 'question_id')->hiddenInput()->label(false) ?>
    <?= $form->field($answer, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($answer, 'answer')->widget(RichTextField::class, [
        'id' => 'answerRichTextField' . ($answer->isNewRecord ? 0 : $answer->id),
        'placeholder' => Yii::t('QuestionsModule.base', 'Provide an answer...'),
        'backupInterval' => 0
    ])->label($answer->isNewRecord) ?>

    <?php if (!$answer->isNewRecord) : ?>
        <?= Button::danger(Yii::t('QuestionsModule.base', 'Cancel'))
            ->action('cancelEdit', Url::toContentAnswer($answer)) ?>
    <?php endif; ?>

    <?= Button::save()->action($answer->isNewRecord ? 'saveAnswer' : 'save') ?>

    <?php ActiveForm::end() ?>

<?= Html::endTag('div') ?>
