<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\modules\content\widgets\WallCreateContentForm;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\permissions\CreateQuestion;
use humhub\modules\space\models\Space;
use humhub\widgets\form\ActiveForm;

class WallCreateForm extends WallCreateContentForm
{
    /**
     * @inheritdoc
     */
    public $submitUrl = Url::ROUTE_QUESTION_CREATE;

    /**
     * @inheritdoc
     */
    public function renderForm()
    {
        return $this->render('form', ['model' => new Question($this->contentContainer)]);
    }

    /**
     * @inheritdoc
     */
    public function renderActiveForm(ActiveForm $form): string
    {
        return $this->render('wall-create-form', [
            'model' => new Question($this->contentContainer),
            'form' => $form,
            'submitUrl' => $this->submitUrl,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return $this->contentContainer instanceof Space
            && $this->contentContainer->permissionManager->can(CreateQuestion::class)
            && parent::beforeRun();
    }

}
