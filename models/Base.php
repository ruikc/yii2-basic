<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Base extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
     * @throws InvalidConfigException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2020/3/24 8:02 下午
     */
    public function afterSave($insert, $changedAttributes)
    {
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
     * @throws InvalidConfigException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2020/3/24 8:02 下午
     */
    public static function findDataById($id)
    {
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
    public static function getStatus($status = '')
    {
        $result = [
            0 => '删除',
            10 => '启用',
            20 => '禁用',
        ];
        if ($status !== '') {
            return $result[$status];
        }
        unset($result[0]);
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
    public static function cacheFind($id, $clean = false, $key = 'id')
    {
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
    public static function modelField($id, $field = 'title', $default = '')
    {
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
    private static function formatArray($array)
    {
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
     * @throws InvalidConfigException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/4/23 19:26
     */
    private static function getTableName()
    {
        return static::getTableSchema()->fullName;
    }

    /**
     * select2得到数据
     * @name: select2
     * @return array|\yii\db\ActiveRecord[]
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/7 10:07 上午
     */
    public static function select2($field = 'name',$except=[0])
    {
        $data = self::find()->andWhere(['>', 'status', 0])->andWhere(['not in','id',$except])->select('id,' . $field . ' as text')->asArray()->all();
        return ArrayHelper::map($data, 'id', 'text');
    }

}
