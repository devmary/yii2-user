<?php

namespace app\modules\newmodule\controllers;

use app\modules\newmodule\models\Settings;
use Yii;
use yii\web\Controller;
use app\modules\newmodule\models\LoginForm;
use app\modules\newmodule\models\SettingsForm;
use app\modules\newmodule\models\User;
use app\modules\newmodule\GoogleAuthenticator;


/**
 * Default controller for the `newmodule` module
 */
class SettingsController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $settings = new SettingsForm();
        if ($settings->load(Yii::$app->request->post()) /*&& $settings->login()*/) {


            $SettingsForm = Yii::$app->request->post('SettingsForm');
            var_dump($SettingsForm['secretCode']);

            //var_dump($settings->active2FA);
            //var_dump($settings->checkCode);
            //var_dump($SettingsForm);


            //$user = new User;
            $user = User::find()->where(['username' => Yii::$app->user->identity->username])->one();
            var_dump($user);
            $user->active_2fa = $SettingsForm['active2FA'];
            $user->secret_2fa = $SettingsForm['secretCode'];
            $user->save();
            //var_dump($user);

            $settings->active2FA = $SettingsForm['active2FA'];
            $settings->secretCode = $SettingsForm['secretCode'];
            $settings->checkCode = $SettingsForm['checkCode'];
            $settings->qrUrl = $SettingsForm['qrUrl'];
            return $this->render('settings', [
                'settings' => $settings,
            ]);
            //return $this->goBack();
        }
        //var_dump($settings->getSecretCode());
        //var_dump($settings->getQR());
        $settings['qrUrl'] = $settings->getQR();
        $settings['active2FA'] = $settings->getStatus2fa();

        return $this->render('settings', [
            'settings' => $settings,
        ]);
    }

    public function actionSecret(){
        $this->layout = false;
        $googleAuth = new GoogleAuthenticator;
        $secret = $googleAuth->createSecret();
        $qrUrl = $googleAuth->getQRCodeGoogleUrl(Yii::$app->name, $secret);
        $result = array(
            'secret' => $secret,
            'qrUrl' => $qrUrl
        );
        $result = json_encode($result);

        return $result;
    }

    public function actionSubmit() {
        $active2fa = $_POST['active'];
        $secretCode = $_POST['secret'];
        $imgUrl = $_POST['imgurl'];
        //var_dump($active2fa);
        if($active2fa = false) {
            $modal = false;
            //var_dump(1);
        } else {
            $modal = true;
            //var_dump(2);
        }
        //$modal = false;
//        var_dump(json_encode($_POST));
        return $modal;
    }

    public function actionCheck() {
        $userSecret = $_POST['secret'];
        $code = $_POST['code'];
        $googleAuth = new GoogleAuthenticator;
        $checkResult = $googleAuth->verifyCode($userSecret, $code, 0);
        /*var_dump($checkResult);
        if ($checkResult) {
            $result = ;
        }*/
        $checkResult = false;
        return $checkResult;
    }



    /**
     * Settings page
     */
    public function actionSettings(){

        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $settings = new SettingsForm();
        if ($settings->load(Yii::$app->request->post()) /*&& $settings->login()*/) {
            return $this->goBack();
        }
        //var_dump($settings->getSecretCode());
        //var_dump($settings->getQR());
        $settings['qrUrl'] = $settings->getQR();
//var_dump(Yii::$app->name);
        return $this->render('settings', [
            'settings' => $settings,
        ]);
    }
}
