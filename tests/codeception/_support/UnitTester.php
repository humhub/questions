<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions;

use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\space\models\Space;
use tests\codeception\_support\HumHubDbTestCase;

class UnitTester extends HumHubDbTestCase
{

    public function createQuestion(string $headline, string $description = '', int $spaceId = 1): ?Question
    {
        $space = Space::findOne(['id' => $spaceId]);

        $question = new Question($space);
        $question->question = $headline;
        $question->description = $description;
        $question->save();

        return $question;
    }

    public function createAnswer(string $text, Question $question): ?QuestionAnswer
    {
        $answer = new QuestionAnswer();
        $answer->question_id = $question->id;
        $answer->answer = $text;
        $answer->save();

        return $answer;
    }
}
