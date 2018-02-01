<?php

namespace app\modules\newmodule\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 * @property string $secret_2fa
 * @property string $active_2fa
 */
class User extends ActiveRecord implements IdentityInterface
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'username', 'password'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 45],
            [['username'], 'string', 'max' => 24],
            [['access_token'], 'string', 'max' => 32],
            [['password', 'auth_key', 'secret_2fa'], 'string', 'max' => 255],
            [['active_2fa'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'secret_2fa' => 'Secret 2fa',
            'active_2fa' => 'Active 2fa',
        ];
    }

    public function init()
    {
        /*$this->on(User::EVENT_BEFORE_LOGIN, function($event){
            var_dump($event);
        });*/
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
//        return $this->password === md5($password);
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function generateAuthKey(){
        return $this->auth_key = \Yii::$app->security->generateRandomString();
    }
}
