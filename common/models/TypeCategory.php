<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "type_category".
 *
 * @property integer $id
 * @property string $category
 *
 * @property TypeCompany[] $typeCompanies
 * @property TypeUser[] $typeUsers
 */
class TypeCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'type_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID категории'),
            'category' => Yii::t('app', 'Название категории'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeCompanies()
    {
        return $this->hasMany(TypeCompany::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeUsers()
    {
        return $this->hasMany(TypeUser::className(), ['category_id' => 'id']);
    }
}
