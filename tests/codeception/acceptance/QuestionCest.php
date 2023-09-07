<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions\acceptance;

use questions\AcceptanceTester;

class QuestionCest
{

    public function testCreateQuestion(AcceptanceTester $I)
    {
        $I->wantTo('create a Question from stream');
        $I->amAdmin();
    }

}
