<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AssetUser;

/**
 * AssetUserSearch represents the model behind the search form about `app\models\AssetUser`.
 */
class AssetUserSearch extends AssetUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_asset_user', 'id_user', 'id_asset', 'quantity'], 'integer'],
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

        $query = AssetUser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_asset_user' => $this->id_asset_user,
            'id_user' => $this->id_user,
            'id_asset' => $this->id_asset,
            'quantity' => $this->quantity,
        ]);

        $query->joinWith(['idUser' => function($query){
            $query->from(['idUser' => 'tbl_user']);
        }]);

        $query->joinWith(['idAsset' => function($query){
            $query->from(['idAsset' => 'asset']);
        }]);

        return $dataProvider;
    }
}
