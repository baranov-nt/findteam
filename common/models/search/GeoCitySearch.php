<?php

namespace common\models\search;

use common\models\forms\GeoCityForm;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GeoCitySearch represents the model behind the search form about `common\models\GeoCity`.
 */
class GeoCitySearch extends GeoCityForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'region_id'], 'integer'],
            [['name_ru', 'name_en', 'okato'], 'safe'],
            [['lat', 'lon'], 'number'],
            [['active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = GeoCityForm::find()
            ->where(['active' => 1])
            ->orderBy('name_ru');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'region_id' => $this->region_id,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'name_en', $this->name_en])
            ->andFilterWhere(['like', 'okato', $this->okato]);

        return $dataProvider;
    }
}
