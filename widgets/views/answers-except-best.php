<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\helpers\Html;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\Answer;
use humhub\modules\questions\widgets\AnswersToggleButton;

/* @var Question $question */
/* @var int|null $currentAnswerId */
/* @var QuestionAnswer[] $answers */
/* @var bool $isDetailView */
/* @var array $options */
?>
<?= Html::a('', null, ['id' => 'answers', 'class' => 'questions-anchor']) ?>

<?php if ($isDetailView) : ?>
    <?= AnswersToggleButton::widget(['count' => count($answers)]) ?>
<?php endif; ?>

<?= Html::beginTag('div', $options) ?>

    <?php foreach ($answers as $answer) : ?>
        <?= Answer::widget([
            'answer' => $answer,
            'highlight' => $answer->id === $currentAnswerId,
            'isDetailView' => $isDetailView
        ]) ?>
    <?php endforeach; ?>

<?= Html::endTag('div') ?>
