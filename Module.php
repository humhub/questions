<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\questions\models\Question;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerModule;
use Yii;

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
    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer instanceof Space) {
            return [
                new permissions\CreateQuestion()
            ];
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [Space::class];
    }

    /**
     * @inheritdoc
     */
    public function getContentClasses(): array
    {
        return [Question::class];
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('QuestionsModule.base', 'Questions');
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        return Yii::t('QuestionsModule.base', 'Allows to create questions.');
    }

}
