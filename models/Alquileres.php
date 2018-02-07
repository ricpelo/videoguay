<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alquileres".
 *
 * @property int $id
 * @property int $socio_id
 * @property int $pelicula_id
 * @property string $created_at
 * @property string $devolucion
 *
 * @property Peliculas $pelicula
 * @property Socios $socio
 */
class Alquileres extends \yii\db\ActiveRecord
{
    public $createdAtForm;

    // /**
    //  * Escenario usado cuando se crea una nueva instancia.
    //  * @var string
    //  */
    // public const ESCENARIO_CREAR = 'crear';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alquileres';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['createdAtForm']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['socio_id', 'pelicula_id'], 'required'],
            [['socio_id', 'pelicula_id'], 'default', 'value' => null],
            [['socio_id', 'pelicula_id'], 'integer'],
            [
                ['createdAtForm'],
                'datetime',
                'timeZone' => Yii::$app->formatter->timeZone,
                'timestampAttribute' => 'created_at',
                'timestampAttributeFormat' => 'php:Y-m-d H:i:s',
            ],
            [['devolucion'], 'safe'],
            [['socio_id', 'pelicula_id', 'created_at'], 'unique', 'targetAttribute' => ['socio_id', 'pelicula_id', 'created_at']],
            [['pelicula_id'], 'exist', 'skipOnError' => true, 'targetClass' => Peliculas::className(), 'targetAttribute' => ['pelicula_id' => 'id']],
            [['socio_id'], 'exist', 'skipOnError' => true, 'targetClass' => Socios::className(), 'targetAttribute' => ['socio_id' => 'id']],
            [['pelicula_id'], function ($attribute, $params, $validator) {
                if (Peliculas::findOne($this->pelicula_id)->estaAlquilada) {
                    $this->addError($attribute, 'La película ya está alquilada');
                }
            }, 'when' => function ($model, $attribute) {
                return $model->id === null;
            }],
            // [['pelicula_id'], function ($attribute, $params, $validator) {
            //     if (Peliculas::findOne($this->pelicula_id)->estaAlquilada) {
            //         $this->addError($attribute, 'La película ya está alquilada');
            //     }
            // }, 'on' => self::ESCENARIO_CREAR],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'socio_id' => 'Socio ID',
            'pelicula_id' => 'Pelicula ID',
            'created_at' => 'Alquilada en',
            'devolucion' => 'Devolucion',
        ];
    }

    public function getEstaPendiente()
    {
        return $this->devolucion === null;
    }

    public function getEstaDevuelto()
    {
        return !$this->estaPendiente;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPelicula()
    {
        return $this->hasOne(Peliculas::className(), ['id' => 'pelicula_id'])->inverseOf('alquileres');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocio()
    {
        return $this->hasOne(Socios::className(), ['id' => 'socio_id'])->inverseOf('alquileres');
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->createdAtForm = Yii::$app->formatter->asDatetime($this->created_at);
    }
}
