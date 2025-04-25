<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/* @var QuestionAnswer $answer */
/* @var array $options */

use humhub\helpers\Html;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\VoteButton;
?>
<?= Html::beginTag('div', $options) ?>

    <?= VoteButton::widget(['answer' => $answer, 'type' => 'up']) ?>

    <div><?= (int) $answer->votes_summary ?></div>

    <?= VoteButton::widget(['answer' => $answer, 'type' => 'down']) ?>

<?= Html::endTag('div') ?>
