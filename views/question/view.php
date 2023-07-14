<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\stream\WallStreamEntryOptions;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\widgets\WallEntry;

/* @var Question $question */
/* @var int $currentAnswerId */
/* @var WallStreamEntryOptions $renderOptions */
?>
<?= WallEntry::widget([
    'model' => $question,
    'currentAnswerId' => $currentAnswerId,
    'renderOptions' => $renderOptions,
]) ?>
