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
                    $socio = Socios::find()
                        ->where([
                            'like',
                            'lower(nombre)',
                            mb_strtolower($value),
                        ])
                        ->one();
                    if ($socio !== null) {
                        $value = $socio->numero;
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
