<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\Answer;
use humhub\modules\questions\widgets\AnswerForm;
use humhub\modules\questions\widgets\AnswersHeader;
use humhub\modules\questions\widgets\AnswerVoting;
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
class AnswerController extends ContentContainerController
{

    /**
     * @param int $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionContent($id)
    {
        $answer = QuestionAnswer::findOne($id);

        if ($answer === null) {
            throw new NotFoundHttpException();
        }

        if (!$answer->question->content->canView()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        return $this->asJson([
            'answer' => $answer->id,
            'content' => Answer::widget([
                'answer' => $answer,
                'highlight' => true
            ])
        ]);
    }

    /**
     * @param int $qid Question ID
     * @param int|null $id Answer ID
     * @return array|string|Response
     * @throws ForbiddenHttpException
     */
    public function actionEdit($qid, $id = null)
    {
        $question = Question::findOne($qid);

        if ($question === null) {
            throw new NotFoundHttpException();
        }

        if ($id === null) {
            $answer = new QuestionAnswer();
            $answer->question_id = $question->id;
        } else {
            $answer = QuestionAnswer::find()
                ->where(['id' => $id, 'question_id' => $qid])
                ->one();
        }

        if ($answer === null) {
            throw new NotFoundHttpException();
        }

        if (!$answer->canEdit()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        $answerFormId = $answer->isNewRecord ? 0 : $answer->id;

        if ($answer->load(Yii::$app->request->post()) && $answer->validate() && $answer->save()) {
            return $this->asJson([
                'success' => true,
                'question' => $question->id,
                'answer' => $answer->id,
                'answerFormId' => $answerFormId,
                'header' => AnswersHeader::widget(['question' => $question]),
                'content' => Answer::widget([
                    'answer' => $answer,
                    'highlight' => true
                ]),
                'form' => $answerFormId === 0 ? AnswerForm::widget(['question' => $question]) : null
            ]);
        }

        return $this->asJson([
            'answerFormId' => $answerFormId,
            'form' => AnswerForm::widget([
                'question' => $question,
                'answer' => $answer
            ])
        ]);
    }

    public function actionVote($id, $vote)
    {
        $this->forcePostRequest();

        $answer = QuestionAnswer::findOne($id);

        if ($answer === null) {
            throw new NotFoundHttpException();
        }

        if (!$answer->getVoteService()->canVote()) {
            throw new ForbiddenHttpException();
        }

        if (!$answer->getVoteService()->vote($vote)) {
            return $this->asJson(['success' => false]);
        }

        // Refresh voting summary after new vote
        $answer->refresh();

        return $this->asJson([
            'success' => true,
            'content' => AnswerVoting::widget(['answer' => $answer])
        ]);
    }

    public function actionSelectBest($id)
    {
        $this->forcePostRequest();

        $answer = QuestionAnswer::findOne($id);

        if ($answer === null) {
            throw new NotFoundHttpException();
        }

        $question = $answer->question;

        if ($question === null) {
            throw new NotFoundHttpException();
        }

        if (!$question->getAnswerService()->canSelectBest()) {
            throw new ForbiddenHttpException();
        }

        if (!$question->getAnswerService()->changeBest($answer)) {
            return $this->asJson(['success' => false]);
        }

        // Refresh the "best" flag after updating
        $answer->refresh();

        return $this->asJson([
            'success' => true,
            'header' => AnswersHeader::widget(['question' => $question]),
            'action' => $answer->is_best ? 'selected' : 'unselected',
            'titleSelect' => Yii::t('QuestionsModule.base', 'Select best answer'),
            'titleUnselect' => Yii::t('QuestionsModule.base', 'Unselect best answer')
        ]);
    }

    /**
     * @param int $id
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $answer = QuestionAnswer::findOne($id);

        if ($answer === null) {
            throw new NotFoundHttpException();
        }

        if (!$answer->canEdit()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        $question = $answer->question;
        $deletedAnswerId = $answer->id;

        if (!$answer->delete()) {
            return $this->asJson([
                'success' => false,
                'message' => Yii::t('QuestionsModule.base', 'Cannot delete the Answer!')
            ]);
        }

        return $this->asJson([
            'success' => true,
            'answer' => $deletedAnswerId,
            'header' => AnswersHeader::widget(['question' => $question]),
            'message' => Yii::t('QuestionsModule.base', 'Deleted')
        ]);
    }

}
