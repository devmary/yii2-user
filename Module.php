<?php

namespace app\modules\newmodule;

use yii\filters\AccessControl;
use Yii;
use app\modules\newmodule\models\LoginForm;

/**
 * newmodule module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\newmodule\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();


        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\newmodule\models\UserIdentity',
            'enableAutoLogin' => true,
            'loginUrl' => ['newmodule/login'],
            /*'on beforeLogin' => function($event)
            {
                var_dump($event);
                if($event['attributes']['active_2fa']) {
                    $response = [
                        'modal' => true,
                        'success' => true
                    ];
                    return json_encode($response);
                } else {
                $model = new LoginForm();
                    $model->login();
                    return $this->goBack();
                }
                Yii::$app->user->identity->beforeLogin($event);
                var_dump(2);die;
            }*/
        ]);

        /*Yii::$app->set('session', [
            'class' => 'yii\web\Session',
            'name' => '_adminSessionId',
        ]);*/

        // custom initialization code goes here
    }

    /*public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],

            ],
        ];
    }*/
}
