<?php

namespace common\models;

use backend\modules\translate\models\SourceMessage;
use Yii;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property string $category
 * @property string $description
 * @property string $location
 * @property string $message
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property SourceMessage[] $sourceMessages
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'description', 'location', 'message'], 'required'],
            [['message'], 'string'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['category'], 'string', 'max' => 16],
            [['description', 'location'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID Контента'),
            'category' => Yii::t('app', 'Категория'),
            'description' => Yii::t('app', 'Описание контента'),
            'location' => Yii::t('app', 'Путь'),
            'message' => Yii::t('app', 'Сообщение'),
            'user_id' => Yii::t('app', 'ID Пользователя'),
            'created_at' => Yii::t('app', 'Дата создания'),
            'updated_at' => Yii::t('app', 'Дата изменения'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessages()
    {
        return $this->hasMany(SourceMessage::className(), ['content_id' => 'id']);
    }
}
