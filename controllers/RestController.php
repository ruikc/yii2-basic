<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\CompositeAuth;

/**
 * Site controller
 */
class RestController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @var array 免登录方法数组，* 代表全部方法免登录
     */
    public $except = [];
//    public $except = ['*'];

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

        //是否需要登录操作
        if (!in_array(Yii::$app->controller->action->id, $this->except) && !in_array('*', $this->except)) {
            $behaviors[] = [
                'class' => CompositeAuth::class,
                'authMethods' => [
                    HttpBearerAuth::class,
//                    QueryParamAuth::className(),
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * 返回正确信息
     * @name: success
     * @param array $data
     * @param string $msg
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/4/4 14:40
     */
    public function success($msg = '', $data = []) {
        if ($data instanceof ActiveDataProvider) {
            $result = [];
            $result['data'] = $data->getModels();
            $result['total'] = $data->totalCount;
            $result['pages'] = $data->pagination->pageCount;
            $data = $result;
        }
        Yii::$app->getResponse()->statusText = $msg;
        Yii::$app->getResponse()->data = $data;
        Yii::$app->getResponse()->send();
        exit;
    }

    /**
     * 扔出错误信息
     * @name: error
     * @param $msg
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/4/11 13:14
     */
    public function error($msg) {
        if (is_array($msg)) {
            foreach ($msg as $key => $val) {
                $msg = $val[0];
                break;
            }
        }
        Yii::$app->getResponse()->setStatusCode(209, $msg);
        Yii::$app->getResponse()->send();
        exit;
    }

}

