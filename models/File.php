<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "file".
 *
 * @property int $id 文件id
 * @property int $user_id 用户id
 * @property string $name 文件名
 * @property string $domain 七牛域名
 * @property string $key 文件key
 * @property string $ext 扩展名
 * @property int $size 上传文件大小
 * @property int $status 状态 10-已启用 0-已删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class File extends \app\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'size', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'domain', 'key'], 'string', 'max' => 100],
            [['ext'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '文件id',
            'user_id' => '用户id',
            'name' => '文件名',
            'domain' => '七牛域名',
            'key' => '文件key',
            'ext' => '扩展名',
            'size' => '上传文件大小',
            'status' => '状态 10-已启用 0-已删除',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }


    /**
     * 得到文件url
     * @name: getUrl
     * @param $id
     * @param string $default
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/11/19 14:40
     */
    public static function getUrl($id, $default = '')
    {
//        if (!$id) return Url::to($default);
        if (!$id) return '';
        $file = self::cacheFind($id);
        if (!$file) {
            return Url::to($default);
        }
        return Yii::$app->qiniu->downFile($file['domain'] . $file['key']);
    }

    /**
     * 得到多文件url
     * @name: getFiles
     * @param $id
     * @param string $default
     * @return array|string
     * @author: lw <19930@qq.com>
     * @time: 2018/12/11 09:40
     */
    public static function getFiles($id, $default = '')
    {
        if (!$id) return $default;
        $ids = explode(',', $id);
        return self::find()->andWhere(['id' => $ids])->select('id,name,user_id,key,ext,size')->asArray()->all();
    }
}
