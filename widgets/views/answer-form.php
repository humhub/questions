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
        'placeholder' => Yii::t('QuestionsModule.base', 'Enter your answer...')
    ])->label($answer->isNewRecord) ?>

    <?php if (!$answer->isNewRecord) : ?>
        <?= Button::danger()->action('cancelEditAnswer') ?>
    <?php endif; ?>

    <?= Button::save()->action('saveAnswer') ?>

    <?php ActiveForm::end() ?>

<?= Html::endTag('div') ?>