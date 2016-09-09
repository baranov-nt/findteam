<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_online".
 *
 * @property integer $user_id
 * @property integer $online
 *
 * @property User $user
 */
class UserOnline extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_online';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['online'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'online' => Yii::t('app', 'Online'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
