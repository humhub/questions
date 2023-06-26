<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;

/**
 * Question WallEntry Widget is used to display a question inside the stream.
 *
 * This Widget will used by the Poll Model in Method getWallOut().
 *
 * @since 0.5
 * @author Luke
 */
class WallEntry extends WallStreamModuleEntryWidget
{
    /**
     * @inheritdoc
     */
    public $createRoute = '/questions/question/create-form';

    /**
     * @inheritdoc
     */
    public $editRoute = '/questionss/questions/edit';

    /**
     * @inheritdoc
     */
    public $createFormSortOrder = 200;

    /**
     * @inheritdoc
     */
    public $createFormClass = WallCreateForm::class;

    /**
     * @inheritDoc
     */
    public function renderContent()
    {
        return $this->render('entry', ['question' => $this->model,
            'user' => $this->model->content->createdBy,
            'contentContainer' => $this->model->content->container]);
    }

    /**
     * @return string a non encoded plain text title (no html allowed) used in the header of the widget
     */
    protected function getTitle()
    {
        return trim($this->model->question) === '' ? $this->model->getContentName() : $this->model->question;
    }
}
