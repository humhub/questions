<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions;

use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerModule;

/**
 * Questions Module is the WebModule for the Q&A system.
 *
 * @package humhub.modules.questions
 * @author Luke
 */
class Module extends ContentContainerModule
{

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [Space::class];
    }

}
