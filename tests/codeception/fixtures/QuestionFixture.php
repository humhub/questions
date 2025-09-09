<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\tests\codeception\fixtures;

use humhub\modules\questions\models\Question;
use yii\test\ActiveFixture;

class QuestionFixture extends ActiveFixture
{
    public $modelClass = Question::class;
    public $dataFile = '@questions/tests/codeception/fixtures/data/question.php';
    public $depends = [
        QuestionAnswerFixture::class,
        QuestionAnswerVoteFixture::class,
    ];
}
