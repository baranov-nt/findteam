<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Связи между пользователями и ролями
 *
 * @property string $item_name
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property AuthItem $itemName
 */
class AuthAssignment extends ActiveRecord
{
    /**
     * Наименование таблицы
     * @return string
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * Автоматическое заполнение создания и редактирования
     * @return array
     */
    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::className(),
        ]];
    }

    /**
     * Правила валдиации
     * @return array
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'], // Обязательно для заполнения
            [['user_id', 'created_at', 'updated_at'], 'integer'],   // Целочисленные значения
            [['item_name'], 'string', 'max' => 64], // Строковые значения (максимум 64 символа)
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * Прользователь
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Роль или допуск
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
}
