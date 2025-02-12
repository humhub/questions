<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions;

use humhub\modules\content\widgets\WallEntryLinks;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\widgets\ProvideAnswerLink;

class Events
{
    public static function onWallEntryLinksInit($event)
    {
        /* @var WallEntryLinks $links */
        $links = $event->sender;
        if ($links->object instanceof Question) {
            $links->addWidget(ProvideAnswerLink::class, ['question' => $links->object], ['sortOrder' => 1]);
        }
    }
}
