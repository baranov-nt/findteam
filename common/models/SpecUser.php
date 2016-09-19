<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spec_user".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $user_id
 *
 * @property ProfileUser $user
 * @property TypeUser $type
 */
class SpecUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spec_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'user_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProfileUser::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeUser::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID пользователя'),
            'type_id' => Yii::t('app', 'Тип пользователя'),
            'user_id' => Yii::t('app', 'Тип пользователя'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(ProfileUser::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TypeUser::className(), ['id' => 'type_id']);
    }
}
