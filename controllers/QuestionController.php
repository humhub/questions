<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\widgets\WallCreateForm;
use humhub\modules\stream\actions\StreamEntryResponse;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
            throw new ForbiddenHttpException('Access denied!');
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
        $question = new Question($this->contentContainer);

        if (!$question->content->canEdit()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        $question->load(Yii::$app->request->post());

        return WallCreateForm::create($question, $this->contentContainer);
    }

    /**
     * @param int $id
     * @return array|string|Response
     * @throws ForbiddenHttpException
     */
    public function actionEdit($id)
    {
        $question = Question::findOne($id);

        if ($question === null) {
            throw new NotFoundHttpException();
        }

        if (!$question->content->canEdit()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        if ($question->load(Yii::$app->request->post())) {
            if ($question->validate() && $question->save()) {
                return StreamEntryResponse::getAsJson($question->content);
            }

            return $this->asJson(['error' => $question->getErrors()]);
        }

        return $this->renderAjax('edit', [
            'question' => $question,
            'options' => [
                'data' => [
                    'question' => $question->id,
                    'content-component' => 'questions.Question',
                    'content-key' => $question->content->id,
                ],
                'class' => 'content_edit'
            ]
        ]);
    }

}
