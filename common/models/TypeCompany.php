<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "type_company".
 *
 * @property integer $id
 * @property string $type
 * @property integer $category_id
 *
 * @property SpecCompany[] $specCompanies
 * @property TypeCategory $category
 */
class TypeCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['type'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID типа компании'),
            'type' => Yii::t('app', 'Тип компании'),
            'category_id' => Yii::t('app', 'ID категории'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpecCompanies()
    {
        return $this->hasMany(SpecCompany::className(), ['type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(TypeCategory::className(), ['id' => 'category_id']);
    }
}
