<?php

namespace common\models\search;

use common\models\ProfileCompanyIdentity;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProfileCompanySearch represents the model behind the search form about `common\models\ProfileCompany`.
 */
class ProfileCompanySearch extends ProfileCompanyIdentity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status'], 'integer'],
            [['name', 'description', 'image_main', 'images', 'inn', 'ogrn', 'okpo', 'okved', 'okato', 'bik', 'kpp'], 'safe'],
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
        $query = ProfileCompanyIdentity::find();

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
            'type' => $this->type,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_main', $this->image_main])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'ogrn', $this->ogrn])
            ->andFilterWhere(['like', 'okpo', $this->okpo])
            ->andFilterWhere(['like', 'okved', $this->okved])
            ->andFilterWhere(['like', 'okato', $this->okato])
            ->andFilterWhere(['like', 'bik', $this->bik])
            ->andFilterWhere(['like', 'kpp', $this->kpp]);

        return $dataProvider;
    }
}
