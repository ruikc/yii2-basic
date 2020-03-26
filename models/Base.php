<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Base extends ActiveRecord
{

    const STATUS_DELETED = 0; //静态变量，状态为删除
    const STATUS_ACTIVE = 10; //静态变量，状态为启用
    const STATUS_UNACTIVE = 20; //静态变量，状态为禁用

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * 更新后，保存到缓存操作
     * @name: afterSave
     * @param bool $insert
     * @param array $changedAttributes
     * @return void
     * @throws \yii\base\InvalidConfigException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2020/3/24 8:02 下午
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $key = static::getPrimaryKey();
        $cacheKey = static::getTableName() . '_id_' . $key;
        Yii::$app->cache->set($cacheKey, $this);
    }

    /**
     * 通过ID得到数据，走缓存的
     * @name: findDataById
     * @param $id
     * @return Base|mixed|null
     * @throws \yii\base\InvalidConfigException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2020/3/24 8:02 下午
     */
    public static function findDataById($id) {
        $cacheKey = static::getTableName() . '_id_' . $id;
        $data = Yii::$app->cache->get($cacheKey);
        if (!$data) {
            $data = static::findOne($id);
            Yii::$app->cache->set($cacheKey, $data);
        }
        return $data;
    }

    /**
     * 得到状态操作
     * @name: getStatus
     * @param $status
     * @return mixed
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 17/11/1 下午3:01
     */
    public static function getStatus($status = '') {
        $result = [
            static::STATUS_DELETED => '删除',
            static::STATUS_ACTIVE => '启用',
            static::STATUS_UNACTIVE => '禁用',
        ];
        if ($status !== '') {
            return $result[$status];
        }
        unset($result[static::STATUS_DELETED]);
        return $result;
    }

    /**
     * 通过缓存查找
     * @name: cacheFind
     * @param $id
     * @param string $key
     * @param bool $clean
     * @return array|mixed|null|ActiveRecord
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2017/12/4 下午3:27
     */
    public static function cacheFind($id, $clean = false, $key = 'id') {
        if (empty($id)) {
            return false;
        }
        if (is_array($id)) {
            $id = static::formatArray($id);
            $cachekey = 'cache_' . static::getTableName() . '_' . $key . '_' . md5(serialize($id));
            $where = $id;
        } else {
            $cachekey = 'cache_' . static::getTableName() . '_' . $key . '_' . $id;
            $where = [$key => $id];
        }

        $result = Yii::$app->cache->get($cachekey);
        if ($result == null || $clean) {
            $result = static::find()->andWhere($where)->asArray()->one();
            if ($result) {
                Yii::$app->cache->set($cachekey, $result, 600);
            }
        }
        return $result;
    }


    /**
     * 得到模板一列
     * @name: modelField
     * @param $id
     * @param string $field
     * @return mixed
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/5/26 09:58
     */
    public static function modelField($id, $field = 'title', $default = '') {
        if (!$id) {
            return $default;
        }
        $model = static::findOne($id);
        return $model[$field];
    }


    /**
     * 格式化数组
     * @name: formatArray
     * @param $array
     * @return mixed
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/4/10 10:08
     */
    private static function formatArray($array) {
        asort($array);
        foreach ($array as $key => $val) {
            $array[$key] = $val . '';
        }
        return $array;
    }

    /**
     * 得到表名称
     * @name: getTableName
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/4/23 19:26
     */
    private static function getTableName() {
        return static::getTableSchema()->fullName;
    }

}
