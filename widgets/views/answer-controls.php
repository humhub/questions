<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\helpers\Html;
use humhub\modules\ui\menu\MenuEntry;
use humhub\widgets\bootstrap\Badge;

/* @var MenuEntry[] $entries */
/* @var array $options */
?>
<?= Html::beginTag('ul', $options) ?>
    <?= Html::beginTag('li', ['class' => 'nav-item dropdown']) ?>
        <?= Html::a('', '#', [
            'class' => 'nav-link dropdown-toggle',
            'data-bs-toggle' => 'dropdown',
            'aria-label' => Yii::t('base', 'Toggle answer control menu'),
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false',
            'role' => 'button',
        ]) ?>

        <ul class="dropdown-menu dropdown-menu-end">
            <?php foreach ($entries as $entry) : ?>
                <li><?= $entry->render() ?></li>
            <?php endforeach; ?>
        </ul>
    <?= Html::endTag('li') ?>
<?= Html::endTag('ul') ?>
