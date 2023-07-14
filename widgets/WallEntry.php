<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\modules\content\widgets\stream\WallStreamEntryOptions;
use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;

/**
 * Question WallEntry Widget is used to display a question inside the stream.
 *
 * This Widget is used by the Question Model in Method getWallOut().
 *
 * @author Luke
 */
class WallEntry extends WallStreamModuleEntryWidget
{
    /**
     * @inheritdoc
     */
    public $createRoute = Url::ROUTE_QUESTION_CREATE_FORM;

    /**
     * @inheritdoc
     */
    public $editRoute = Url::ROUTE_QUESTION_EDIT;

    /**
     * @inheritdoc
     */
    public $createFormSortOrder = 200;

    /**
     * @inheritdoc
     */
    public $createFormClass = WallCreateForm::class;

    /**
     * @inheritdoc
     * @var Question $model
     */
    public $model;

    public ?int $currentAnswerId = null;

    /**
     * @inheritDoc
     */
    public function renderContent()
    {
        return $this->render('wall-entry', [
            'question' => $this->model,
            'currentAnswerId' => $this->currentAnswerId,
            'isDetailView' => $this->renderOptions->isViewContext(WallStreamEntryOptions::VIEW_CONTEXT_DETAIL),
            'options' => [
                'data' => [
                    'question' => $this->model->id,
                    'content-component' => 'questions.Question',
                    'content-key' => $this->model->content->id
                ]
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function getTitle()
    {
        return trim($this->model->question) === '' ? $this->model->getContentName() : $this->model->question;
    }
}
