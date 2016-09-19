<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 04.07.2016
 * Time: 11:32
 */

namespace common\models\search;

use common\models\AuthItem;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Поиск по допускам и ролям
 * Class AuthItemSearch
 * @package common\models
 */
class AuthItemSearch extends AuthItem
{
    const COUNT = 50; // количество ролей/допусков на одной странице

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data'], 'safe'], // Безопасные аттрибуты
            [['type', 'created_at', 'updated_at'], 'integer'],  // Целочисленные значения
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
        $query = AuthItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=> $this::COUNT,
            ],
            'sort' => array(
                'defaultOrder' => ['type' => SORT_ASC, 'name' => SORT_ASC], // Сначала роли, а потом допуски
            ),
        ]);

        $this->load($params);
        
        // Если валидация не пройдена, то ничего не выводить
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // Фильтрация
        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
