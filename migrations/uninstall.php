<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\components\Migration;

class uninstall extends Migration
{
    public function up()
    {
        $this->safeDropTable('question_answer_vote');
        $this->safeDropTable('question_answer');
        $this->safeDropTable('question');
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }
}
