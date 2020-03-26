<?php

namespace app\controllers;

use app\models\Config;
use app\models\Dept;
use app\models\Menu;
use app\models\Role;
use Yii;
use yii\data\Pagination;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class BaseController extends Controller
{
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
        // 权限验证
        $this->_auth();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['POST'],
            ],
        ];
        return $behaviors;
    }

    /**
     * 权限验证简化版
     * @name: _auth
     * @return bool|\yii\web\Response
     * @throws ForbiddenHttpException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/6 1:51 下午
     */
    private function _auth() {

        $user = Yii::$app->user;
        //如果没有登录就跳转到登录页面
        if ($user->isGuest) {
            return $this->redirect('site/login');
        }
        if ($user->id == 1) {
            return true;
        }
        // 非管理员用户组
        if ($user->identity->role_id != 0) {
            $menu = Menu::find()->where(['url' => '/' . Yii::$app->controller->route])->one();
            // 没找到菜单的情况下
            if (!$menu) {
                $this->_no_auth();
            }

            $role_count = Role::find()->where(['=', 'id', $user->identity->role_id])
                ->andWhere(new Expression('FIND_IN_SET(:url, rules)'))
                ->addParams([':url' => $menu['id']])
                ->count();

            // 有菜单且没权限
            if (!$role_count) {
                $this->_no_auth();
            }
        }
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