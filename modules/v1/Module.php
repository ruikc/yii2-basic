<?php

namespace app\modules\v1;

use yii\web\Response;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
        \Yii::$app->setComponents([
            'response' => [
                'class' => 'yii\web\Response',
                'format' => Response::FORMAT_JSON,
                'charset' => 'UTF-8',
                'on beforeSend' => function ($event) {
                    $response = $event->sender;
                    $data = [];
                    $data = [
                        'status' => $response->getStatusCode(),
                        'message' => $response->statusText
                    ];
                    if (isset($response->data['data'])) {
                        $data['data'] = $response->data['data'];
                        $data['total'] = $response->data['total'];
                    }else{
                        $data['data']= $response->data;
                    }
                    $response->data = $data;
                    if (empty($response->data['data'])) {
                        unset($response->data['data']);
                    }
                    if ($response->data['status'] == 401) {
                        $response->data['message'] = '账号已经退出，请重新登录';
                    }

                    $response->setStatusCode(200);
                    $response->format = Response::FORMAT_JSON;
                },
            ],
        ]);

        // custom initialization code goes here
    }
}
