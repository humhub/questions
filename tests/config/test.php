<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\questions\tests\codeception\fixtures\QuestionFixture;

return [
    'modules' => ['questions'],
    'fixtures' => [
        'default',
        'question' => QuestionFixture::class,
    ],
];
