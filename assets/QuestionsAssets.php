<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\assets;

use humhub\components\assets\AssetBundle;

class QuestionsAssets extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@questions/resources';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/humhub.questions.Question.js',
        'js/humhub.questions.Answer.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/questions.min.css',
    ];
}
