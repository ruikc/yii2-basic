<?php


namespace app\components;

use Yii;
use yii\helpers\Html;

/**
 * Class ActionColumn
 * @package app\widgets
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = '查看';
                        $additionalOptions['class'] = isset($additionalOptions['class']) ? $additionalOptions['class'] . ' modal_show' : 'modal_show';
                        $additionalOptions['data-url'] = $url;
                        $url = 'javascript:void(0);';
                        break;
                    case 'update':
                        $title = '编辑';
                        $additionalOptions['class'] = isset($additionalOptions['class']) ? $additionalOptions['class'] . ' modal_show' : 'modal_show';
                        $additionalOptions['data-url'] = $url;
                        $url = 'javascript:void(0);';
                        break;
                    case 'delete':
                        $title = '删除';
                        $additionalOptions['data-method'] = 'post';
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '1',
                ], $additionalOptions, $this->buttonOptions);
                return Html::a($title, $url, $options);
            };
        }
    }
}