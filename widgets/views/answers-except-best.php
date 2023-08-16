<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\Answer;
use humhub\modules\questions\widgets\AnswersToggleButton;

/* @var Question $question */
/* @var int|null $currentAnswerId */
/* @var QuestionAnswer[] $answers */
/* @var bool $enableControls */
/* @var array $options */
?>
<?php if ($enableControls) : ?>
    <?= AnswersToggleButton::widget(['count' => count($answers)]) ?>
<?php endif; ?>

<?= Html::beginTag('div', $options) ?>

    <?php foreach ($answers as $answer) : ?>
        <?= Answer::widget([
            'answer' => $answer,
            'highlight' => $answer->id === $currentAnswerId,
            'enableControls' => $enableControls
        ]) ?>
    <?php endforeach; ?>

<?= Html::endTag('div') ?>
