<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\components\Migration;

/**
 * Class m230626_112542_initial
 */
class m230626_112542_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->safeCreateTable('question', [
            'id' => $this->primaryKey(),
            'question' => $this->string()->notNull(),
            'description' => $this->text(),
        ]);

        $this->safeCreateTable('question_answer', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer()->notNull(),
            'answer' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->safeDropTable('question_answer');
        $this->safeDropTable('question');
    }
}
