<?php

namespace app\models;

use yii\helpers\Html;

/**
 * This is the model class for table "peliculas".
 *
 * @property int $id
 * @property string $codigo
 * @property string $titulo
 * @property string $precio_alq
 *
 * @property Alquileres[] $alquileres
 */
class Peliculas extends \yii\db\ActiveRecord
{
    public $todo;

    private $_pendiente;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'peliculas';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['todo']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo', 'titulo', 'precio_alq'], 'required'],
            [['codigo', 'precio_alq'], 'number'],
            [['titulo'], 'string', 'max' => 255],
            [['codigo'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Código',
            'titulo' => 'Título',
            'precio_alq' => 'Precio alquiler',
            'todo' => 'Todo',
        ];
    }

    public function getEnlace()
    {
        return Html::a(Html::encode($this->titulo), [
            'peliculas/view',
            'id' => $this->id,
        ]);
    }
    /**
     * Comprueba si una película está alquilada.
     * @return bool Si la película está alquilada o no.
     */
    public function getEstaAlquilada()
    {
        $alquiler = $this->getAlquileres()
            ->where(['devolucion' => null])
            ->one();

        $this->_pendiente = $alquiler;

        return $alquiler !== null;
    }

    public function getPendiente()
    {
        if ($this->_pendiente === null) {
            $this->getEstaAlquilada();
        }
        return $this->_pendiente;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlquileres()
    {
        return $this->hasMany(Alquileres::className(), ['pelicula_id' => 'id'])->inverseOf('pelicula');
    }

    public function getSocios()
    {
        return $this->hasMany(Socios::className(), ['id' => 'socio_id'])
            ->via('alquileres');
    }
}
