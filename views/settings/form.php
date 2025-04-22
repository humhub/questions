<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\questions\models\forms\ContainerSettings;
use humhub\modules\space\models\Space;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;

/* @var ContainerSettings $settings */
?>
<div class="card">
    <div class="card-header"><?= Yii::t('QuestionsModule.base', '<strong>Questions</strong> settings') ?></div>
    <div class="card-body">
        <div class="text-body-secondary">
            <?= $settings->contentContainer instanceof Space
                ? Yii::t('QuestionsModule.base', 'Settings of the "Questions" module for this single Space.')
                : Yii::t('QuestionsModule.base', 'Settings of the "Questions" module for your Profile.')?>
        </div>
        <br>
        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($settings, 'showAnswersInStream')->checkbox() ?>

        <?= Button::save()->submit() ?>

        <?php ActiveForm::end() ?>
    </div>
</div>
