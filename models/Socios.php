<?php

namespace app\models;

use yii\helpers\Html;

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
            'numero' => 'Número',
            'nombre' => 'Nombre',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
        ];
    }

    public function getEnlace()
    {
        return Html::a(Html::encode($this->nombre), [
            'socios/view',
            'id' => $this->id,
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPendientes()
    {
        return $this->getAlquileres()
            ->where(['devolucion' => null])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlquileres()
    {
        return $this->hasMany(Alquileres::className(), ['socio_id' => 'id'])->inverseOf('socio');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeliculas()
    {
        return $this->hasMany(Peliculas::className(), ['id' => 'pelicula_id'])
            ->via('alquileres');
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            $id = \Yii::$app->db->createCommand(
                'INSERT INTO socios_id DEFAULT VALUES RETURNING id'
            )->queryScalar();

            $this->id = $id;
        }
        return true;
    }
}
