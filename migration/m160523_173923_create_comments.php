<?php

use yii\db\Migration;

/**
 * Handles the creation for table `comments`.
 * Has foreign keys to the tables:
 *
 * - `todo`
 */
class m160523_173923_create_comments extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'todoId' => $this->integer()->notNull(),
            'comment' => $this->string(5000)->notNull(),
            'comment_date' => $this->dateTime()->notNull(),
            'commentId' => $this->integer()->notNull(),
        ]);

        // creates index for column `todoId`
        $this->createIndex(
            'idx-comments-todoId',
            'comments',
            'todoId'
        );

        // add foreign key for table `todo`
        $this->addForeignKey(
            'fk-comments-todoId',
            'comments',
            'todoId',
            'todo',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `todo`
        $this->dropForeignKey(
            'fk-comments-todoId',
            'comments'
        );

        // drops index for column `todoId`
        $this->dropIndex(
            'idx-comments-todoId',
            'comments'
        );

        $this->dropTable('comments');
    }
}
