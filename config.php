<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\content\widgets\WallEntryLinks;
use humhub\modules\questions\Events;

return [
    'id' => 'questions',
    'class' => 'humhub\modules\questions\Module',
    'namespace' => 'humhub\modules\questions',
    'events' => [
        [WallEntryLinks::class, WallEntryLinks::EVENT_INIT, [Events::class, 'onWallEntryLinksInit']],
    ],
];
