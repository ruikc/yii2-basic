<?php

namespace app\modules\models;

use app\models\Dept;
use app\models\File;
use app\widgets\Utils;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Auth
 * @package app\modules\models
 */
class Auth extends \app\models\Auth
{
    /**
     * {@inheritdoc}
     */
    public function fields() {
        $fields = parent::fields();
        unset($fields['status'], $fields['auth_key'], $fields['password_hash'], $fields['role_id'], $fields['is_admin'], $fields['mobile']);
        unset($fields['created_at'], $fields['updated_at']);
        $fields['dept_name'] = function ($model) {
            return $model->dept->title;
        };
        return $fields;
    }

}
