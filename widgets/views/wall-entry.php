<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\models\Question;

/* @var Question $question */
/* @var array $attributes */
?>
<?= Html::beginTag('div', $attributes) ?>
    <?= RichText::output($question->description) ?>
<?= Html::endTag('div') ?>
