<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Alquileres;

/**
 * AlquileresSearch represents the model behind the search form of `app\models\Alquileres`.
 */
class AlquileresSearch extends Alquileres
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'socio_id', 'pelicula_id'], 'integer'],
            [['created_at', 'devolucion'], 'safe'],
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
        $query = Alquileres::find();

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
            'socio_id' => $this->socio_id,
            'pelicula_id' => $this->pelicula_id,
            'created_at' => $this->created_at,
            'devolucion' => $this->devolucion,
        ]);

        return $dataProvider;
    }
}
