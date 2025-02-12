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
            'votes_summary' => $this->integer()->defaultValue(0)->notNull(),
            'is_best' => $this->boolean()->defaultValue(0)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ]);
        $this->safeAddForeignKey('fk_questions_question_id', 'question_answer', 'question_id', 'question', 'id', 'CASCADE');
        $this->safeCreateIndex('idx_is_best', 'question_answer', 'is_best');
        $this->safeAddForeignKey('fk_questions_created_by', 'question_answer', 'created_by', 'user', 'id', 'CASCADE');
        $this->safeAddForeignKey('fk_questions_updated_by', 'question_answer', 'updated_by', 'user', 'id', 'CASCADE');

        $this->safeCreateTable('question_answer_vote', [
            'answer_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'type' => $this->tinyInteger(1)->notNull(),
        ]);
        $this->safeAddPrimaryKey('pk_question_answer_vote', 'question_answer_vote', 'answer_id,user_id');
        $this->safeAddForeignKey('fk_questions_answer_id', 'question_answer_vote', 'answer_id', 'question_answer', 'id', 'CASCADE');
        $this->safeAddForeignKey('fk_questions_user_id', 'question_answer_vote', 'user_id', 'user', 'id', 'CASCADE');
        $this->safeCreateIndex('idx_type', 'question_answer_vote', 'type');
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
