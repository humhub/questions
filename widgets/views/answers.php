<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\questions\assets\QuestionsAssets;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\Answer;

/* @var QuestionAnswer $bestAnswer */

QuestionsAssets::register($this);
?>
<?php if ($bestAnswer) : ?>
    <?= Answer::widget(['answer' => $bestAnswer]) ?>
<?php endif; ?>

<div class="except-best-answers"></div>
