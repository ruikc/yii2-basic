<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app".
 *
 * @property int $id 应用id
 * @property string $version 版本号
 * @property int $file_id 上件文件
 * @property int $status 状态 10-已启用 20-未启用 0-已删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $is_force 是否强制更新
 * @property string $intro 应用介绍
 */
class App extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'app';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'status', 'created_at', 'updated_at', 'is_force'], 'integer'],
            [['version'], 'string', 'max' => 10],
            [['intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '应用id',
            'version' => '版本号',
            'file_id' => '上件文件',
            'status' => '状态', //10-已启用 20-未启用 0-已删除
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'is_force' => '强制更新', //是否强制更新
            'intro' => '应用介绍',
        ];
    }

}
