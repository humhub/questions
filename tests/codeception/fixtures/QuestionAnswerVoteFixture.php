<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\tests\codeception\fixtures;

use humhub\modules\questions\models\QuestionAnswerVote;
use yii\test\ActiveFixture;

class QuestionAnswerVoteFixture extends ActiveFixture
{
    public $modelClass = QuestionAnswerVote::class;
    public $dataFile = '@questions/tests/codeception/fixtures/data/question_answer_vote.php';
}
