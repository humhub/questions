<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\tests\codeception\fixtures;

use humhub\modules\questions\models\QuestionAnswer;
use yii\test\ActiveFixture;

class QuestionAnswerFixture extends ActiveFixture
{
    public $modelClass = QuestionAnswer::class;
    public $dataFile = '@questions/tests/codeception/fixtures/data/question_answer.php';
}
