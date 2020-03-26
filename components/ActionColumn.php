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
    protected function initDefaultButton($name, $iconName, $additionalOptions = []) {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = '查看';
                        break;
                    case 'update':
                        $title = '编辑';
                        break;
                    case 'delete':
                        $title = '删除';
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                return Html::a($title, $url, $options);
            };
        }
    }
}