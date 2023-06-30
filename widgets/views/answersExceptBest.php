<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\Answer;
use humhub\modules\questions\widgets\AnswersHeader;

/* @var Question $question */
/* @var QuestionAnswer[] $answers */
?>
<?= AnswersHeader::widget(['question' => $question]) ?>

<?php foreach ($answers as $answer) : ?>
    <?= Answer::widget(['answer' => $answer]) ?>
<?php endforeach; ?>
