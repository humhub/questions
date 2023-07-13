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
use humhub\modules\questions\widgets\AnswersHeader;
use humhub\modules\questions\widgets\AnswersToggleButton;

/* @var Question $question */
/* @var QuestionAnswer[] $answers */
/* @var array $options */
?>
<?= Html::beginTag('div', $options) ?>

    <?= AnswersHeader::widget(['question' => $question]) ?>

    <?php foreach ($answers as $answer) : ?>
        <?= Answer::widget(['answer' => $answer]) ?>
    <?php endforeach; ?>

    <?= AnswersToggleButton::widget(['hideButton' => count($answers) ? 'expand' : 'all']) ?>

<?= Html::endTag('div') ?>
