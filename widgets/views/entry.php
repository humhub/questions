<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\models\Question;

/* @var Question $question */
?>
<div>
    <div data-ui-markdown>
        <?= RichText::output($question->description) ?>
    </div>
</div>
