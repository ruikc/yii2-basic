<?php

namespace app\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * BaseController implements the CRUD actions for User model.
 */
class BaseController extends Controller
{
    /**
     * @var array 免登录方法数组，* 代表全部方法免登录
     */
    public $except = [];

    /**
     *
     * @name: beforeAction
     * @param \yii\base\Action $action
     * @return bool|void
     * @throws \yii\web\BadRequestHttpException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2020/5/23 10:16 下午
     */
    public function beforeAction($action) {
        parent::beforeAction($action);
        //是否需要登录操作
        if (!in_array(Yii::$app->controller->action->id, $this->except) && !in_array('*', $this->except)) {
            // 权限验证
            return $this->_auth();
        }
        return true;
    }

    /**
     * 重写行为，配置接口以json形式返回数据
     * 允许跨域操作
     * @name: behaviors
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2017/12/7 上午10:18
     */
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * 权限验证简化版
     * @name: _auth
     * @return bool|void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2020/5/23 10:15 下午
     */
    private function _auth() {
        $user = Yii::$app->user;
        //如果没有登录就跳转到登录页面
        if ($user->isGuest) {
            return $this->redirect(['/site/login'])->send();
        }
        if (!$user->identity->is_admin) {
            return false;
        }
        return true;
    }

    /**
     * 没有授权的操作
     * @name: _no_auth
     * @return void
     * @throws ForbiddenHttpException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019-05-31 16:38
     */
    private function _no_auth() {
        $request = Yii::$app->request;
        if ($request->isAjax && (!$request->headers->has('X-PJAX') || !$request->headers->get('X-PJAX'))) {
            $this->error('未授权访问');
        } else {
            throw new ForbiddenHttpException('未授权访问');
        }
    }

    /**
     * 返回正确信息
     * @name: success
     * @param $data
     * @param $msg
     * @return \yii\web\Response
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/4/14 18:12
     */
    public function success($msg, $data = []) {
        return $this->asJson(['error' => 0, 'data' => ArrayHelper::toArray($data), 'msg' => $msg]);
    }

    /**
     * 返回错误信息
     * @name: ajaxError
     * @param $msg
     * @return \yii\web\Response
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/4/14 18:00
     */
    public function error($msg) {
        return $this->asJson(['error' => 1, 'msg' => $msg]);
    }

    /**
     * 得到模板一列
     * @name: getField
     * @param $model
     * @param $where
     * @param string $field
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/10/25 14:55
     */
    public function getField($query, $field = 'title') {
        if (!$query) {
            return '';
        }
        $model = $query->select($field)
            ->asArray()
            ->one();

        return $model[$field];
    }
}