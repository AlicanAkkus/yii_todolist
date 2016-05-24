<?php

use yii\db\Migration;

/**
 * Handles the creation for table `todo`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m160523_172946_create_todo extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('todo', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'title' => $this->string(50)->notNull(),
            'status' => $this->integer()->notNull(),
            'description' => $this->string(500)->notNull(),
            'createDate' => $this->dateTime()->notNull(),
            'updateDate' => $this->dateTime()->notNull(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            'idx-todo-userId',
            'todo',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-todo-userId',
            'todo',
            'userId',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-todo-userId',
            'todo'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-todo-userId',
            'todo'
        );

        $this->dropTable('todo');
    }
}
