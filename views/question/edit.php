<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\helpers\Html;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\questions\assets\QuestionsAssets;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;
use yii\web\View;

/* @var View $this */
/* @var Question $question */
/* @var string $context */
/* @var array $options */

QuestionsAssets::register($this);
?>
<?= Html::beginTag('div', $options) ?>

    <?php $form = ActiveForm::begin() ?>

    <?= Html::hiddenInput('context', $context) ?>

    <?= $form->field($question, 'question')->textInput([
            'placeholder' => Yii::t('QuestionsModule.base', 'Question headline...')
        ]) ?>

    <?= $form->field($question, 'description')->widget(RichTextField::class, [
            'placeholder' => Yii::t('QuestionsModule.base', 'Question details...')
        ]) ?>

    <?= Button::primary(Yii::t('QuestionsModule.base', 'Save'))
        ->action('submitEditForm', Url::toEditQuestion($question))
        ->submit() ?>

    <?= Button::danger(Yii::t('QuestionsModule.base', 'Cancel'))
        ->action('cancelEditForm') ?>

    <?php ActiveForm::end() ?>

<?= Html::endTag('div') ?>
