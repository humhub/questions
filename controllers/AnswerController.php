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
     * @param int $qid
     * @param int|null $id
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
            $answer = new QuestionAnswer($this->contentContainer);
            $answer->question_id = $question->id;
        } else {
            $answer = QuestionAnswer::find()
                ->where(['id' => $id, 'question_id' => $qid])
                ->one();
        }

        if ($answer === null) {
            throw new NotFoundHttpException();
        }

        if (!$answer->content->canEdit()) {
            throw new ForbiddenHttpException('Access denied!');
        }

        if ($answer->load(Yii::$app->request->post()) && $answer->validate() && $answer->save()) {
            return $this->asJson([
                'success' => true,
                'question' => $question->id,
                'answer' => $answer->id,
                'header' => Yii::t('QuestionsModule.base', '{count} Answers', [
                    'count' => $question->getAnswerService()->getCount()
                ]),
                'content' => Answer::widget([
                    'answer' => $answer,
                    'highlight' => true
                ])
            ]);
        }

        return $this->renderAjax('form', [
            'answer' => $answer
        ]);
    }

    public function actionVote($id, $vote)
    {
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

        $answer->refresh();

        return $this->asJson([
            'success' => true,
            'content' => AnswerVoting::widget(['answer' => $answer])
        ]);
    }

}
