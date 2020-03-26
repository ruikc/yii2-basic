<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Auth;

/**
 * UserSearch represents the model behind the search form of `app\models\Auth`.
 */
class UserSearch extends Auth
{
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'role_id', 'expired_at', 'status', 'created_at', 'updated_at', 'dept_id', 'is_admin'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'access_token', 'real_name', 'mobile', 'sex'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Auth::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => ['id'] //提供排序的列，默认全部
            ]
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
            'role_id' => $this->role_id,
            'expired_at' => $this->expired_at,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dept_id' => $this->dept_id,
            'is_admin' => $this->is_admin,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'access_token', $this->access_token])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'sex', $this->sex]);

        $query->andWhere(['>', 'status', 0])->andWhere(['>', 'id', 1]);

        return $dataProvider;
    }
}
