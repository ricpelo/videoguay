<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $password
 * @property string $email
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    const ESCENARIO_CREATE = 'create';
    const ESCENARIO_UPDATE = 'update';

    /**
     * Atributo usado para guardar el campo de "confirmar contraseña" del
     * formulario de creación de usuarios.
     * @var string
     */
    public $password_repeat;

    /**
     * Contiene la foto del usuario subida en el formulario.
     * @var UploadedFile
     */
    public $foto;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'password_repeat',
            'foto',
        ]);
    }

    // public function scenarios()
    // {
    //     $padre = parent::scenarios();
    //     return array_merge($padre, [
    //         self::ESCENARIO_UPDATE => $padre[self::ESCENARIO_CREATE],
    //     ]);
    // }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['password', 'password_repeat'], 'required', 'on' => self::ESCENARIO_CREATE],
            [['nombre', 'password', 'password_repeat', 'email'], 'string', 'max' => 255],
            [
                ['password_repeat'],
                'compare',
                'compareAttribute' => 'password',
                'skipOnEmpty' => false,
                'on' => [self::ESCENARIO_CREATE, self::ESCENARIO_UPDATE],
            ],
            [['nombre'], 'unique'],
            [['email'], 'default'],
            [['email'], 'email'],
            [['foto'], 'file', 'extensions' => 'jpg'],
        ];
    }

    public function upload()
    {
        if ($this->foto === null) {
            return true;
        }
        $nombre = Yii::getAlias('@uploads/') . $this->id . '.jpg';
        $res = $this->foto->saveAs($nombre);
        if ($res) {
            Image::thumbnail($nombre, 80, null)->save($nombre);
        }
        return $res;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'password' => 'Contraseña',
            'email' => 'Dirección de e-mail',
            'password_repeat' => 'Confirmar contraseña',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Comprueba si la contraseña indicada es la contraseña del usuario.
     * @param  string $password La contraseña.
     * @return bool             Si es una contraseña válida o no.
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(
            $password,
            $this->password
        );
    }

    /**
     * Comprueba si es un usuario permitido.
     *
     * Un usuario permitido es aquel usuario logueado que se llama 'pepe' o
     * 'juan'.
     *
     * @return bool Si el usuario es permitido o no.
     */
    public static function getPermitido()
    {
        return !Yii::$app->user->isGuest
            && in_array(Yii::$app->user->identity->nombre, ['pepe', 'juan']);
    }

    public function getRutaImagen()
    {
        $nombre = Yii::getAlias('@uploads/') . $this->id . '.jpg';
        if (file_exists($nombre)) {
            return Url::to('/uploads/') . $this->id . '.jpg';
        }
        return Url::to('/uploads/') . 'default.jpg';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->auth_key = Yii::$app->security->generateRandomString();
                if ($this->scenario === self::ESCENARIO_CREATE) {
                    $this->password = Yii::$app->security->generatePasswordHash($this->password);
                }
            } else {
                if ($this->scenario === self::ESCENARIO_UPDATE) {
                    if ($this->password === '') {
                        $this->password = $this->getOldAttribute('password');
                    } else {
                        $this->password = Yii::$app->security->generatePasswordHash($this->password);
                    }
                }
            }
            return true;
        }
        return false;
    }
}
