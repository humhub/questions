<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\helpers\Html;
use humhub\modules\questions\assets\QuestionsAssets;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\widgets\AnswerForm;
use humhub\modules\questions\widgets\Answers;
use humhub\widgets\bootstrap\Button;
use yii\web\View;

/* @var View $this */
/* @var Question $question */
/* @var int|null $currentAnswerId */
/* @var bool $isDetailView */
/* @var array $options */

QuestionsAssets::register($this);
?>
<?= Html::beginTag('div', $options) ?>

    <?php if ($isDetailView) : ?>
        <?= AnswerForm::widget(['question' => $question]) ?>
    <?php endif; ?>

    <?php if (!$isDetailView) : ?>
        <br>
        <?php if ($question->canAnswer()) : ?>
            <?= Button::info(Yii::t('QuestionsModule.base', 'Provide an answer'))
                ->link(Url::toCreateAnswer($question))
                ->options(['target' => '_blank'])
                ->loader(false) ?>
        <?php endif; ?>

        <?= Button::info(Yii::t('QuestionsModule.base', 'View all answers ({count})', [
                'count' => '<span class="questions-answers-count">' . $question->getAnswerService()->getCount() . '</span>'
            ]))
            ->link(Url::toViewAnswers($question))
            ->options(['target' => '_blank'])
            ->cssClass('active')
            ->loader(false) ?>
    <?php endif; ?>

    <?= Answers::widget([
        'question' => $question,
        'currentAnswerId' => $currentAnswerId,
        'isDetailView' => $isDetailView
    ]) ?>

<?= Html::endTag('div') ?>
