<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\content\components\ContentContainerControllerAccess;
use humhub\modules\questions\models\forms\ContainerSettings;
use humhub\modules\space\models\Space;
use Yii;

/**
 * SettingsController handles settings per Container(Space/User).
 *
 * @package humhub.modules.questions.controllers
 * @author Luke
 */
class SettingsController extends ContentContainerController
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [[ContentContainerControllerAccess::RULE_USER_GROUP_ONLY => [Space::USERGROUP_ADMIN]]];
    }

    public function actionIndex()
    {
        $settings = new ContainerSettings(['contentContainer' => $this->contentContainer]);

        if ($settings->load(Yii::$app->request->post()) && $settings->save()) {
            $this->view->saved();
        }

        return $this->render('form', ['settings' => $settings]);
    }

}
