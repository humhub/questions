<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\questions\assets\QuestionsAssets;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;
use humhub\widgets\Button;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var View $this */
/* @var Question $question */
/* @var array $options */

QuestionsAssets::register($this);
?>
<?= Html::beginTag('div', $options) ?>

    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($question, 'question')->textInput([
            'placeholder' => Yii::t('QuestionsModule.base', 'Question')
        ]) ?>

    <?= $form->field($question, 'description')->widget(RichTextField::class, [
            'placeholder' => Yii::t('QuestionsModule.base', 'Edit your question...')
        ]) ?>

    <?= Button::primary(Yii::t('QuestionsModule.base', 'Save'))
        ->action('submitEditForm', Url::toEditQuestion($question))
        ->submit() ?>

    <?= Button::danger(Yii::t('QuestionsModule.base', 'Cancel'))
        ->action('cancelEditForm') ?>

    <?php ActiveForm::end() ?>

<?= Html::endTag('div') ?>
