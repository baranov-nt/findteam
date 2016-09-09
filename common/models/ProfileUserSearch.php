<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Identity;

/**
 * UserSearch represents the model behind the search form about `common\models\Identity`.
 */
class ProfileUserSearch extends Identity
{
    public $item_name;
    public $tariff_name;
    public $online;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['alias', 'username', 'email', 'phone', 'full_phone', 'description', 'image_main', 'images', 'password_hash', 'auth_key', 'password_reset_token',
                'email_confirm_token', 'item_name', 'tariff_name', 'online'], 'safe'],
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
    public function search($params, $pageSize = 10, $theCreator = false)
    {
        $query = Identity::find()
            ->joinWith(['assignment', 'userOnline', 'profileUser'])->orderBy(['id' => SORT_DESC]);

        if ($theCreator === false)
        {
            $query->where(['!=', 'item_name', 'Создатель']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'or',
            ['like', 'item_name', $this->item_name],
            ['like', 'item_name', $this->tariff_name],
        ]);*/

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'item_name' => $this->item_name,
            'tariff' => $this->tariff_name,
        ]);

        $query->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'full_phone', $this->full_phone])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image_main', $this->image_main])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email_confirm_token', $this->email_confirm_token]);

        return $dataProvider;
    }
}
