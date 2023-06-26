<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\permissions\CreateQuestion;
use humhub\modules\questions\widgets\WallCreateForm;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * QuestionController handles all question related actions.
 *
 * @package humhub.modules.questions.controllers
 * @author Luke
 */
class QuestionController extends ContentContainerController
{

    /**
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionCreateForm()
    {
        if (!(new Question($this->contentContainer))->content->canEdit()) {
            throw new ForbiddenHttpException();
        }

        return $this->renderAjaxPartial(WallCreateForm::widget([
            'contentContainer' => $this->contentContainer,
        ]));
    }

    /**
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        if (!$this->contentContainer->permissionManager->can(new CreateQuestion())) {
            throw new ForbiddenHttpException('Access denied!');
        }
        
        $question = new Question();
        $question->load(Yii::$app->request->post());

        return WallCreateForm::create($question, $this->contentContainer);
    }

}
