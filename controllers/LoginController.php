<?php

namespace app\modules\newmodule\controllers;

use app\modules\newmodule\models\UserIdentity;
use Yii;
use app\modules\newmodule\models\LoginForm;
use yii\web\Response;

class LoginController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        var_dump(Yii::$app->user);die;
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionValidation() {
        //var_dump(Yii::$app->request->post());
        $model = new LoginForm();
        if ($model->validate()) {

        }
    }

    public function actionAjaxLogin(){

        if( Yii::$app->request->isAjax ){
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
//                    var_dump(Yii::$app->request->post());
                    //if (Yii::$app->request->post('login-button')) {
                        $user = $model->getUser();
                        if($user['attributes']['active_2fa']) {
                            $response = [
                                'modal' => true,
                                'success' => true
                            ];
                            return json_encode($response);
                        } else {
                            $model->login();
                            return $this->goBack();
                        }
//var_dump($user['attributes']['secret_2fa']);
//var_dump($user['attributes']['active_2fa']);

                   /* }
                    else {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        $result = array(
                            'data' => \yii\widgets\ActiveForm::validate($model),
                            'success' => false
                        );
                        return json_encode($result);
//                        return \yii\widgets\ActiveForm::validate($model);
                    }*/
                }
                //echo 2;
                //var_dump($model->validate());die;
               /* if ($model->login()) {
                    //var_dump($model);die;
                    //var_dump(Yii::$app->response);die;
                    //return $this->goBack();
                    $response = [
                        'modal' => true,
                        'success' => 'true'
                    ];
                    return json_encode($response);
                }*/ else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $result = array(
                        'data' => \yii\widgets\ActiveForm::validate($model),
                        'success' => false
                    );
                    return json_encode($result);
//                    return \yii\widgets\ActiveForm::validate($model);
                }
            }
        }
        else {
           // print_r(12);
            throw new \yii\web\HttpException(404 ,'Page not found');
        }
    }

    public function actionCheckCode() {

        if (Yii::$app->request->isAjax) {
            $model = new LoginForm();

            if ($model->load(Yii::$app->request->post())) {
                $user = $model->getUser();
                $LoginForm = Yii::$app->request->post('LoginForm');
                $code = $LoginForm['checkCode'];
                $secret = $user['attributes']['secret_2fa'];
//                var_dump($secret);
//                var_dump($code);
                $result = $model->checkCode($secret, $code);
                //var_dump($result);
//                var_dump($user);
                if ($result) {

                    $model->login();
                    //die;
                    return $this->goBack();
                } else {
                    return 'error';
                }
            }
        }
        //return 123;
    }

}
