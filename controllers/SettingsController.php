<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\questions\models\forms\ContainerSettings;
use Yii;

/**
 * SettingsController handles settings per Container(Space/User).
 *
 * @package humhub.modules.questions.controllers
 * @author Luke
 */
class SettingsController extends ContentContainerController
{

    public function actionIndex()
    {
        $settings = new ContainerSettings(['contentContainer' => $this->contentContainer]);

        if ($settings->load(Yii::$app->request->post()) && $settings->save()) {
            $this->view->saved();
        }

        return $this->render('form', ['settings' => $settings]);
    }

}
