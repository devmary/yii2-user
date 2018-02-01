<?php

namespace app\modules\newmodule\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
//use yii\web\User;
use app\modules\newmodule\GoogleAuthenticator;
use app\modules\newmodule\models\UserAuth;

class SettingsForm extends Model
{
    public $username;
    public $active2FA;
    public $secretCode;
    public $checkCode;
    public $qrUrl;

    private $_user;

    public function init()
    {
        $this->username = Yii::$app->user->identity->username;
        $user = $this->getUser();
        //var_dump($user);
        $this->_user = UserIdentity::findByUsername($this->username);
        //var_dump($this->_user);
        //var_dump(UserIdentity::findByUsername($this->username));
    }

    public function getSecretCode(){

        $user = new UserAuth();
        //var_dump($user);
        //var_dump(Yii::$app->user->identity->secret_2fa);
        //var_dump(Yii::$app->user->getIdentity());
        $googleAuth = new GoogleAuthenticator;

        if(Yii::$app->user->identity->secret_2fa) {
            $this->secretCode = Yii::$app->user->identity->secret_2fa;
        } else {
            $this->secretCode = $googleAuth->createSecret();
        }

        return $this->secretCode;
    }

    public function getQR(){
        $secret = $this->getSecretCode();
        $googleAuth = new GoogleAuthenticator;
        $this->qrUrl = $googleAuth->getQRCodeGoogleUrl(Yii::$app->name, $secret);
        return $this->qrUrl;
    }

    public function getStatus2fa(){
        if(Yii::$app->user->identity->active_2fa) {
            $this->active2FA = Yii::$app->user->identity->active_2fa;
        }

        return $this->active2FA;
    }

    public function saveUserField(){
        $user = $this->getUser();
        var_dump($user);
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = UserIdentity::findByUsername($this->username);
        }

        return $this->_user;
    }


}