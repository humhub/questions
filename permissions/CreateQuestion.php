<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\permissions;

use humhub\libs\BasePermission;
use humhub\modules\space\models\Space;
use Yii;

/**
 * CreateQuestion Permission
 */
class CreateQuestion extends BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        Space::USERGROUP_OWNER,
        Space::USERGROUP_ADMIN,
        Space::USERGROUP_MODERATOR,
        Space::USERGROUP_MEMBER,
    ];

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        Space::USERGROUP_OWNER,
        Space::USERGROUP_ADMIN,
        Space::USERGROUP_USER,
        Space::USERGROUP_GUEST,
    ];

    /**
     * @inheritdoc
     */
    protected $moduleId = 'questions';

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('QuestionsModule.base', 'Can participate in Q&A');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('QuestionsModule.base', 'Can create questions as well a give answers');
    }
}
