<?php

namespace app\models;

use yii\base\Model;

class GestionarPeliculaForm extends Model
{
    /**
     * El número del socio.
     * @var string
     */
    public $numero;
    /**
     * El código de la película.
     * @var string
     */
    public $codigo;

    public function formName()
    {
        return '';
    }

    public function attributeLabels()
    {
        return [
            'numero' => 'Número de socio',
            'codigo' => 'Código de película',
        ];
    }

    public function rules()
    {
        return [
            [['numero', 'codigo'], 'required'],
            [['numero', 'codigo'], 'default'],
            [['numero'], 'filter', 'filter' => function ($value) {
                if (!ctype_digit($value)) {
                    $socio = \app\models\Socios::find()->where(['ilike', 'nombre', $value])->one();
                    if ($socio !== null) {
                        $value = $socio->numero;
                    } else {
                        $value = 0;
                    }
                }
                return $value;
            }],
            [['codigo'], 'filter', 'filter' => function ($value) {
                if (!ctype_digit($value)) {
                    $pelicula = \app\models\Peliculas::find()->where(['ilike', 'titulo', $value])->one();
                    if ($pelicula !== null) {
                        $value = $pelicula->codigo;
                    } else {
                        $value = 0;
                    }
                }
                return $value;
            }],
            [['numero', 'codigo'], 'integer', 'enableClientValidation' => false],
            [
                ['numero'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Socios::className(),
                'targetAttribute' => ['numero' => 'numero'],
            ],
            [
                ['codigo'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Peliculas::className(),
                'targetAttribute' => ['codigo' => 'codigo'],
            ],
        ];
    }
}
