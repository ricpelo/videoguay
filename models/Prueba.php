<?php

namespace app\models;

use yii\base\Model;

class Prueba extends Model
{
    public $uno;
    public $dos;

    private $_pepe;

    public function rules()
    {
        return [
            [['pepe'], 'required'],
            [['uno'], 'trim'],
            [['dos'], 'integer', 'skipOnEmpty' => false],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['pepe']);
    }

    public function getPepe()
    {
        return $this->_pepe;
    }

    public function setPepe($pepe)
    {
        $this->_pepe = $pepe;
    }
}
