<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Правила допусков
 *
 * @property string $name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends ActiveRecord
{
    /**
     * Название таблицы
     * @return string
     */
    public static function tableName()
    {
        return 'auth_rule';
    }

    /**
     * Автоподстановка времени созадния и обновления
     * @return array
     */
    public function behaviors()
    {
        return [[
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => 'updated_at',
            'value' => time(),
        ]];
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'required'], // Обязательно для заполнения
            [['name'], 'unique'],   // Уникальное значение
            [['data'], 'string'],   // Строка
            [['name'], 'string', 'max' => 64],  // Строка (64 символа максимум)
        ];
    }

    /**
     * Названия полей аттрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('app', 'Название'),
            'data' => \Yii::t('app', 'Данные'),
            'created_at' => \Yii::t('app', 'Созданы'),
            'updated_at' => \Yii::t('app', 'Обновлены'),
        ];
    }

    /**
     * Допуски, имеющие текущее правило
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }

    /**
     * Сериализация данных перед валидацией
     * Проверка существования класса (файла с правилами)
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->data) {
            if (class_exists($this->data)) {
                $class = new $this->data();
                $this->data = serialize($class);
            } else {
                $this->addError('data', \Yii::t('app', 'Класс не найден'));
            }
        }

        return true;
    }

    /**
     * Массив всех правил
     * @return array - [Название => Название]
     */
    public static function getAll()
    {
        $rules = [];
        $model = self::find()
            ->all();
        if ($model) {
            foreach ($model as $m) {
                $rules[$m->name] = $m->name;
            }
        }
        return $rules;
    }
}
