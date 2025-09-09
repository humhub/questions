<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\content\widgets\stream\WallStreamEntryOptions;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\widgets\AnswersExceptBest;
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

        $context = Yii::$app->request->get('context', Yii::$app->request->post('context'));

        if ($question->load(Yii::$app->request->post())) {
            if ($question->validate() && $question->save()) {
                if ($context === WallStreamEntryOptions::VIEW_CONTEXT_DETAIL) {
                    $renderOptions = new WallStreamEntryOptions();
                    $renderOptions->viewContext($context);
                } else {
                    $renderOptions = null;
                }

                return StreamEntryResponse::getAsJson($question->content, $renderOptions);
            }

            return $this->asJson(['error' => $question->getErrors()]);
        }

        return $this->renderAjax('edit', [
            'question' => $question,
            'context' => $context,
            'options' => [
                'data' => [
                    'question' => $question->id,
                    'content-component' => 'questions.Question',
                    'content-key' => $question->content->id,
                ],
                'class' => 'content_edit',
            ],
        ]);
    }

    /**
     * @param int $id Question ID
     * @param int|null $aid Answer ID
     */
    public function actionView($id, $aid = null)
    {
        $question = Question::findOne($id);

        if ($question === null) {
            throw new NotFoundHttpException();
        }

        if (!$question->content->canView()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        $renderOptions = new WallStreamEntryOptions();
        $renderOptions->viewContext(WallStreamEntryOptions::VIEW_CONTEXT_DETAIL);

        return $this->render('view', [
            'question' => $question,
            'currentAnswerId' => $aid,
            'renderOptions' => $renderOptions,
        ]);
    }

    public function actionLoadExceptBest($id)
    {
        $this->forcePostRequest();

        $question = Question::findOne($id);

        if ($question === null) {
            throw new NotFoundHttpException();
        }

        if (!$question->content->canView()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        return AnswersExceptBest::widget(['question' => $question]);
    }

}
