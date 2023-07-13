<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\content\widgets\WallCreateContentFormFooter;
use humhub\modules\questions\models\Question;
use humhub\modules\ui\form\widgets\ActiveForm;

/* @var $model Question */
/* @var $form ActiveForm */
/* @var $submitUrl string */
?>

<?= $form->field($model, 'question')
    ->textInput(['placeholder' => Yii::t('QuestionsModule.base', 'Question')])
    ->label(false) ?>

<div class="contentForm_options">
    <?= $form->field($model, 'description')->widget(RichTextField::class, [
        'placeholder' => Yii::t('QuestionsModule.base', 'Description')
    ])->label(false) ?>
</div>

<?= WallCreateContentFormFooter::widget([
    'contentContainer' => $model->content->container,
    'submitUrl' => $submitUrl,
]) ?>
