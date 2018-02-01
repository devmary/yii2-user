<?php

namespace app\modules\newmodule\models;

use Yii;

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
class Settings extends \yii\db\ActiveRecord
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
            [['first_name', 'last_name', 'username', 'password', 'secret_2fa', 'active_2fa'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 45],
            [['username'], 'string', 'max' => 24],
            [['password', 'access_token', 'secret_2fa'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
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
}
