<?php

namespace common\models\search;

use common\models\forms\GeoCountryForm;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GeoCountrySearch represents the model behind the search form about `common\models\GeoCountry`.
 */
class GeoCountrySearch extends GeoCountryForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phone_number_digits_code', 'system_measure', 'active'], 'integer'],
            [['continent', 'name_ru', 'timezone', 'iso2', 'short_name', 'long_name', 'iso3', 'numcode', 'un_member', 'calling_code', 'cctld', 'currency'], 'safe'],
            [['lat', 'lon'], 'number'],
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
        $query = GeoCountryForm::find()
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
            'lat' => $this->lat,
            'lon' => $this->lon,
            'phone_number_digits_code' => $this->phone_number_digits_code,
            'system_measure' => $this->system_measure,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'continent', $this->continent])
            ->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'timezone', $this->timezone])
            ->andFilterWhere(['like', 'iso2', $this->iso2])
            ->andFilterWhere(['like', 'short_name', $this->short_name])
            ->andFilterWhere(['like', 'long_name', $this->long_name])
            ->andFilterWhere(['like', 'iso3', $this->iso3])
            ->andFilterWhere(['like', 'numcode', $this->numcode])
            ->andFilterWhere(['like', 'un_member', $this->un_member])
            ->andFilterWhere(['like', 'calling_code', $this->calling_code])
            ->andFilterWhere(['like', 'cctld', $this->cctld])
            ->andFilterWhere(['like', 'currency', $this->currency]);

        return $dataProvider;
    }
}
