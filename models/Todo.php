<?php

namespace AlicanAkkus\yii_todolist\models;

use Yii;

/**
 * This is the model class for table "todo".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $title
 * @property integer $status
 * @property string $description
 * @property string $createDate
 * @property string $updateDate
 */
class Todo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'todo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'title', 'description'], 'required'],
            [['userId', 'status'], 'integer'],
            [['createDate', 'updateDate'], 'safe'],
            [['title'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'title' => 'Title',
            'status' => 'Status',
            'description' => 'Description',
            'createDate' => 'Create Date',
            'updateDate' => 'Update Date',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasOne(User::className(), ['ID' => 'userId']);//bizdeki bir kayıt 1 kayıta denk geliyor ilişkiler
    }
}
