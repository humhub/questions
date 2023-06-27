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
            'votes' => $this->integer()->defaultValue(0)->notNull()
        ]);
        $this->safeAddForeignKey('fk_questions_question_id', 'question_answer', 'question_id', 'question', 'id', 'CASCADE');

        $this->safeCreateTable('question_answer_vote', [
            'answer_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->tinyInteger()->notNull()
        ]);
        $this->safeAddForeignKey('fk_questions_answer_id', 'question_answer_vote', 'answer_id', 'question_answer', 'id', 'CASCADE');
        $this->safeAddForeignKey('fk_questions_user_id', 'question_answer_vote', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->safeDropTable('question_answer_vote');
        $this->safeDropTable('question_answer');
        $this->safeDropTable('question');
    }
}
