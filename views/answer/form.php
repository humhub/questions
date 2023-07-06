<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\questions\assets\QuestionsAssets;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var View $this */
/* @var QuestionAnswer $answer */
/* @var array $options */

QuestionsAssets::register($this);
?>
<?php ModalDialog::begin(['header' => Yii::t('QuestionsModule.base', '<strong>Create</strong> Answer')]) ?>
    <?php $form = ActiveForm::begin() ?>

    <div class="modal-body">
        <?= $form->field($answer, 'answer')->widget(RichTextField::class, [
            'placeholder' => Yii::t('QuestionsModule.base', 'Enter your answer...')
        ]) ?>
    </div>

    <div class="modal-footer">
        <?= ModalButton::submitModal()?>
        <?= ModalButton::cancel()?>
    </div>

    <?php ActiveForm::end() ?>

<?php ModalDialog::end() ?>
