<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
            [['devolucion', 'pelicula.titulo'], 'safe'],
            [['created_at'], 'default'],
            [
                ['created_at'],
                'datetime',
                'timeZone' => Yii::$app->formatter->timeZone,
                'format' => Yii::$app->formatter->datetimeFormat,
                'timestampAttribute' => 'created_at',
                'timestampAttributeFormat' => 'php:Y-m-d H:i:s',
            ],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['pelicula.titulo']);
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Alquileres::find()->joinWith('pelicula');

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

        $dataProvider->sort->attributes['pelicula.titulo'] = [
            'asc' => ['peliculas.titulo' => SORT_ASC],
            'desc' => ['peliculas.titulo' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'socio_id' => $this->socio_id,
            'pelicula_id' => $this->pelicula_id,
            'created_at' => $this->created_at,
            'devolucion' => $this->devolucion,
        ]);

        $query->andFilterWhere([
            'ilike',
            'peliculas.titulo',
            $this->getAttribute('pelicula.titulo'),
        ]);

        return $dataProvider;
    }
}
