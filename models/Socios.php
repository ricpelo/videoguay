<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "socios".
 *
 * @property int $id
 * @property string $numero
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 *
 * @property Alquileres[] $alquileres
 */
class Socios extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'socios';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numero', 'nombre'], 'required'],
            [['numero', 'telefono'], 'number'],
            [['nombre', 'direccion'], 'string', 'max' => 255],
            [['numero'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero' => 'Numero',
            'nombre' => 'Nombre',
            'direccion' => 'Direccion',
            'telefono' => 'Telefono',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlquileres()
    {
        return $this->hasMany(Alquileres::className(), ['socio_id' => 'id'])->inverseOf('socio');
    }
}
