<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Поиск правил допусков
 * Class AuthRuleSearch
 * @package common\models
 */
class AuthRuleSearch extends AuthRule
{
    const COUNT = 50; // количество правил на одной странице

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'data'], 'safe'], //  Безопасные аттрибуты
            [['created_at', 'updated_at'], 'integer'],  // Целочисленные значения
        ];
    }

    /**
     * Сценарии
     * @return array
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Создает DataProvider на основе переданных данных
     * @param $params - параметры
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AuthRule::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=> $this::COUNT,
            ],
        ]);

        $this->load($params);

        // Если валидация не пройдена, то ничего не выводить
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // Фильтрация
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
