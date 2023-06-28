<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/* @var QuestionAnswer $answer */
/* @var array $options */

use humhub\libs\Html;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\widgets\Button;
?>
<?= Html::beginTag('div', $options) ?>

    <?= Button::defaultType()->icon('caret-up')
        ->action('voteUp') ?>

    <div><?= $answer->votes_count ?></div>

    <?= Button::defaultType()->icon('caret-down')
        ->action('voteDown') ?>

<?= Html::endTag('div') ?>
