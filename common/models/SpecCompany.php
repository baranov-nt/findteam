<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "spec_company".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $company_id
 *
 * @property ProfileCompany $company
 * @property TypeCompany $type
 */
class SpecCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spec_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'company_id'], 'integer'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProfileCompany::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCompany::className(), 'targetAttribute' => ['type_id' => 'id']],
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
            'company_id' => Yii::t('app', 'Тип пользователя'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(ProfileCompany::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TypeCompany::className(), ['id' => 'type_id']);
    }
}
