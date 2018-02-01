<?php

namespace app\models;

use yii\base\Model;

class GestionarSocioForm extends Model
{
    /**
     * El número del socio.
     * @var string
     */
    public $numero;

    public function formName()
    {
        return '';
    }

    public function attributeLabels()
    {
        return [
            'numero' => 'Número de socio',
        ];
    }

    public function rules()
    {
        return [
            [['numero'], 'required'],
            [['numero'], 'default'],
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
            [['numero'], 'integer', 'enableClientValidation' => false],
            [
                ['numero'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Socios::className(),
                'targetAttribute' => ['numero' => 'numero'],
            ],
        ];
    }
}
