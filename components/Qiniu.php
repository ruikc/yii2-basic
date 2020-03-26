<?php

namespace app\components;

use Qiniu\Auth;
use Qiniu\Http\Error;
use Qiniu\Storage\UploadManager;
use yii\helpers\ArrayHelper;

/**
 * Class Qiniu
 * 七牛上传组件
 * @package common\component
 */
class Qiniu extends \yii\base\Component
{
    public $ak;
    public $sk;
    public $domain;
    public $bucket;
    public $fops;
    public $pipeline;
    public $is_private = false;

    public $mineAll = [
        '.3gp' => 'video/3gpp',
        '.apk' => 'application/vnd.android.package-archive',
        '.asf' => 'video/x-ms-asf',
        '.avi' => 'video/x-msvideo',
        '.bin' => 'application/octet-stream',
        '.bmp' => 'image/bmp',
        '.c"=>"text/plain',
        '.class"=>"application/octet-stream',
        '.conf' => 'text/plain',
        '.cpp' => 'text/plain',
        '.doc' => 'application/msword',
        '.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        '.xls' => 'application/vnd.ms-excel',
        '.xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        '.exe' => 'application/octet-stream',
        '.gif' => 'image/gif',
        '.gtar' => 'application/x-gtar',
        '.gz' => 'application/x-gzip',
        '.h"=>"text/plain',
        '.htm' => 'text/html',
        '.html' => 'text/html',
        '.jar' => 'application/java-archive',
        '.java' => 'text/plain',
        '.jpeg' => 'image/jpeg',
        '.jpg' => 'image/jpeg',
        '.js' => 'application/x-javascript',
        '.log' => 'text/plain',
        '.m3u' => 'audio/x-mpegurl',
        '.m4a' => 'audio/mp4a-latm',
        '.m4b' => 'audio/mp4a-latm',
        '.m4p' => 'audio/mp4a-latm',
        '.m4u' => 'video/vnd.mpegurl',
        '.m4v' => 'video/x-m4v',
        '.mov' => 'video/quicktime',
        '.mp2' => 'audio/x-mpeg',
        '.mp3' => 'audio/x-mpeg',
        '.mp4' => 'video/mp4',
        '.mpc' => 'application/vnd.mpohun.certificate',
        '.mpe' => 'video/mpeg',
        '.mpeg' => 'video/mpeg',
        '.mpg' => 'video/mpeg',
        '.mpg4' => 'video/mp4',
        '.mpga' => 'audio/mpeg',
        '.msg' => 'application/vnd.ms-outlook',
        '.ogg' => 'audio/ogg',
        '.pdf' => 'application/pdf',
        '.png' => 'image/png',
        '.pps' => 'application/vnd.ms-powerpoint',
        '.ppt' => 'application/vnd.ms-powerpoint',
        '.pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        '.prop' => 'text/plain',
        '.rc' => 'text/plain',
        '.rmvb' => 'audio/x-pn-realaudio',
        '.rtf' => 'application/rtf',
        '.sh' => 'text/plain',
        '.tar' => 'application/x-tar',
        '.tgz' => 'application/x-compressed',
        '.txt' => 'text/plain',
        '.wav' => 'audio/x-wav',
        '.wma' => 'audio/x-ms-wma',
        '.wmv' => 'audio/x-ms-wmv',
        '.wps' => 'application/vnd.ms-works',
        '.xml' => 'text/plain',
        '.z"=>"application/x-compress',
        '.zip' => 'application/x-zip-compressed',
    ];

    public $mine = [
        'image' => ['image/jpeg', 'image/png', 'image/gif'],
        'video' => ['video/*', 'audio/x-pn-realaudio',],
        'android' => ['application/vnd.android.package-archive'],
        'office' => [
            'application/vnd.ms-powerpoint',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/pdf',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
    ];

    /**
     * 得到token
     * @name: getToken
     * @param string $type
     * @return mixed
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/12/3 3:43 下午
     */
    public function getToken($type = 'image', $persistentNotifyUrl = '', $policy = []) {

        if (isset($this->mine[$type])) {
            $policy = ArrayHelper::merge($policy, [
                'mimeLimit' => implode(';', $this->mine[$type]),
            ]);
        }

        if ($type == 'video') {
            $policy = ArrayHelper::merge($policy, [
                'saveKey' => $this->setVideoKey(),
                'persistentOps' => $this->saveAs(),
                'persistentNotifyUrl' => $persistentNotifyUrl ? $persistentNotifyUrl : \yii\helpers\Url::to(['/qiniu/qiniu'], true),
                'persistentPipeline' => $this->pipeline
            ]);
        }
        $result['uptoken'] = $this->qiniuToken($policy);
        $result['domain'] = $this->domain;
        return $result;
    }

    /**
     * 上传文件
     * @name: putFile
     * @param $filename
     * @param $filepath
     * @param string $key
     * @return bool|mixed
     * @throws \Exception
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/12/9 2:31 下午
     */
    public function putFile($filename, $filepath, $key = '') {
        if (!$key) {
            $key = $this->setKey($filename);
        }
        $token = $this->qiniuToken([]);
        $up = new UploadManager();
        try {
            list($ret, $err) = $up->putFile($token, $key, $filepath);
        } catch (\Exception $err) {
//            return $err->getMessage();
            return false;
        }
        if ($err) {
            return false;
        }
        $ret['domain'] = $this->domain;
        return $ret;
    }

    /**
     * 私有空间下载文件
     * @name: downFile
     * @param $baseUrl
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/12/9 2:34 下午
     */
    public function downFile($baseUrl) {
        if ($this->is_private) {
            $auth = new Auth($this->ak, $this->sk);
            return $auth->privateDownloadUrl($baseUrl);
        }
        return $baseUrl;
    }

    /**
     * 得到token
     * @name: qiniuToken
     * @param array $policy
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/12/9 2:27 下午
     */
    private function qiniuToken($policy = []) {
        $auth = new Auth($this->ak, $this->sk);
        return $auth->uploadToken($this->bucket, null, 3600, $policy);
    }

    /**
     * 设置视频转码
     * @name: saveAs
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/10/12 09:49
     */
    public function saveAs() {
        $saveKey = \Qiniu\base64_urlSafeEncode($this->bucket . ':' . $this->setVideoKey());
        return $this->fops . '|saveas/' . $saveKey;
    }


    /**
     * 设置七牛上传视频转码文件名
     * @name: setVideoKey
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2016/10/28 下午11:21
     */
    private function setVideoKey() {
        $ext = $this->getKeyByFops();
        $filename = 'V' . date('Ymd', time()) . '_' . time() . '_' . rand(1000, 9999);
        return $filename . '.' . $ext;
    }

    /**
     * 得到扩展名
     * @name: getKeyByFops
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time:
     */
    private function getKeyByFops() {
        $fops = $this->fops;
        if (!$fops || strpos($fops, 'mp4') !== false) {
            return 'mp4';
        }
        if (strpos($fops, 'm3u8') !== false) {
            return 'm3u8';
        }
    }

    /**
     * 生成上传文件名称
     * @name: key
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/9/29 16:38
     */
    private function setKey($filename) {
        return date('Ymd', time()) . '_' . uniqid('qn') . '.' . $this->getExt($filename);
    }

    /**
     * 返回扩展名
     * @name: getExt
     * @param $filename
     * @return mixed
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/9/30 11:01
     */
    public function getExt($filename) {
        $tmp = explode('.', $filename);
        return $tmp[count($tmp) - 1];
    }

}