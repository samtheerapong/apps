<?php

namespace app\modules\sam\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sam\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `app\modules\sam\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['docs', 'images', 'ref'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Employee::find();

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
        ]);

        $query->andFilterWhere(['like', 'docs', $this->docs])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'ref', $this->ref]);

        return $dataProvider;
    }
}
