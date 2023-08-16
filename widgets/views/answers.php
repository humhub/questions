<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\questions\assets\QuestionsAssets;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\Answer;
use humhub\modules\questions\widgets\AnswersExceptBest;

/* @var Question $question */
/* @var QuestionAnswer $bestAnswer */
/* @var int $limit */
/* @var bool $isDetailView */
/* @var int|null $currentAnswerId */

QuestionsAssets::register($this);
?>
<?= Answer::widget([
    'answer' => $bestAnswer,
    'isDetailView' => $isDetailView
]) ?>

<?php if ($limit !== 0) : ?>
    <?= AnswersExceptBest::widget([
        'question' => $question,
        'currentAnswerId' => $currentAnswerId,
        'isDetailView' => $isDetailView,
        'limit' => $limit
    ]) ?>
<?php endif; ?>
