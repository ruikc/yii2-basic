<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\LoginForm;

class SiteController extends BaseController
{
    public $except = ['login', 'captcha', 'error'];

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => null,
                'maxLength' => 5,
                'minLength' => 5
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * 修改密码
     * @name: actionReset
     * @return string|Response
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/11/7 14:35
     */
    public function actionReset() {

        $model = new ResetForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->reset()) {
                Yii::$app->session->setFlash('success', '密码修改成功');
                return $this->redirect(['reset']);
            }
        }
        return $this->render('reset', [
            'model' => $model,
        ]);
    }




    /**
     * 移动端下载
     * @name: actionMobile
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2020/1/6 8:24 下午
     */
    public function actionMobile() {
        return $this->render('mobile', ['model' => null]);
    }
}
