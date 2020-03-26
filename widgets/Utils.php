<?php

namespace app\widgets;

use Yii;
use yii\base\BaseObject;
use yii\httpclient\Client;

/**
 * Class Utils
 * @package common\widgets
 */
class Utils extends BaseObject
{
    /**
     * 判断是否为手机号码
     * @name: is_mobile
     * @param $mobile
     * @return false|int
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018-12-26 14:05
     */
    public static function is_mobile($mobile) {
        $rule = '/1[3458679]{1}\d{9}$/';
        return preg_match($rule, $mobile);
    }

    /**
     * 截取字符串长度
     * @name: get_short
     * @param $str
     * @param int $length
     * @param string $ext
     * @return string
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/10/9 11:08
     */
    public static function get_short($str, $length = 40, $ext = '...') {
        $tmpStr = strip_tags(htmlspecialchars_decode($str));
        $tmpStr = preg_replace('/^(&nbsp;|\s)*|(&nbsp;|\s)*$/', '', $tmpStr);
        $tmpLen = mb_strlen($tmpStr, 'utf-8');

        $tmpStr = mb_substr($tmpStr, 0, $length, 'utf-8');
        $tmpStr = trim($tmpStr, chr(0xc2) . chr(0xa0));
        return $tmpStr . ($tmpLen > $length ? '...' : '');
    }


    /**
     * 下载文件
     * @name: down_file
     * @param $url
     * @param $filename
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/10/12 16:32
     */
    public static function downLoad($url, $filename) {
        $header = get_headers($url, 1);
        $size = $header['Content-Length'];
        $fp = fopen($url, 'rb');
        if ($fp === false) exit('文件不存在或打开失败');

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $header['Content-Type']);

        self::setFileName($filename);
//        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);

        set_time_limit(0);

        $chunkSize = 1024 * 1024;
        while (!feof($fp)) {
            $buffer = fread($fp, $chunkSize);
            echo $buffer;
            ob_flush();
            flush();
        }
        fclose($fp);
        ob_clean();
        ob_end_flush();
        exit;
    }

    /**
     * 设置下载文件名
     * @name: setFileName
     * @param $filename
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019-01-19 12:48
     */
    public static function setFileName($filename) {
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) { // www.jbxue.com
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $filename . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $filename . '"');
        }
        header('Cache-Control: max-age=0');//禁止缓存
    }

    /**
     * 统计字数长度，汉字与字母全是一个字符处理
     * @name: str_len
     * @param $str
     * @return int
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019-06-13 16:43
     */
    public static function str_len($str) {
        $str = htmlspecialchars_decode($str);
        $str = strip_tags($str);
        $str = htmlspecialchars_decode($str);
        $strlenth = 0;
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
        foreach ($match[0] as $v) {
            preg_match("/[\xe0-\xef][\x80-\xbf]{2}/", $v, $matchs);
            if (!empty($matchs[0])) {
                $strlenth += 1;
            } elseif (is_numeric($v)) {
                $strlenth += 1;    // 字符字节长度比例 汉字为1
            } else {
                $strlenth += 1;    // 字符字节长度比例 汉字为1
            }
        }
        return $strlenth;
    }

    /**
     * 用换行符号进行分隔
     * @name: lineSplit
     * @param $content
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019-04-17 13:39
     */
    public static function lineSplit($content) {
        $order = ["\r\n", "\n", "\r"];
        $content = str_replace($order, PHP_EOL, $content);
        foreach ($result = explode(PHP_EOL, $content) as $key => $val) {
            $result[$key] = str_replace($order, PHP_EOL, $val);
        }
        return $result;
    }

    /**
     *  * 生成不重复的随机数字
     *  * @param  int $start  需要生成的数字开始范围
     *  * @param  int $end    结束范围
     *  * @param  int $length 需要生成的随机数个数
     *  * @return number      生成的随机数
     *  */
    public static function getRandNumber($length = 8, $start = 1, $end = 9) {
        //初始化变量为0
        $num = 0;
        //建一个新数组
        $temp = [];
        while ($num < $length) {
            //在一定范围内随机生成一个数放入数组中
            $temp[] = mt_rand($start, $end);
            //$data = array_unique($temp);
            //去除数组中的重复值用了“翻翻法”，就是用array_flip()把数组的key和value交换两次。这种做法比用 array_unique() 快得多。
            $data = array_flip(array_flip($temp));
            //将数组的数量存入变量count中
            $num = count($data);
        }
        //为数组赋予新的键名
        shuffle($data);
        //数组转字符串
        $str = implode(',', $data);
        //替换掉逗号
        $number = str_replace(',', '', $str);
        return $number;
    }

    /**
     * 从聚合得到信息
     * @name: curlData
     * @param $url
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/10/11 4:44 下午
     */
    public static function curlData($url, $data = []) {
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }
        var_dump($url);
        $client = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->setMethod('GET')
            ->send();
        return $response->data;
    }

    /**
     * 生成树
     * @name: genTree
     * @param $items
     * @param string $pid
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/7 2:45 下午
     */
    public static function genTree($items, $pid = "parent_id") {
        $map = [];
        $tree = [];
        foreach ($items as &$it) {
            $map[$it['id']] = &$it;
        }  //数据的ID名生成新的引用索引树
        foreach ($items as &$it) {
            $parent = &$map[$it[$pid]];
            if ($parent) {
                $parent['nodes'][] = &$it;
            } else {
                $tree[] = &$it;
            }
        }
        return $tree;
    }

    /**
     * 计算差距年
     * @name: diffYear
     * @param $start
     * @param $end
     * @return int
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/13 2:34 下午
     */
    public static function diffYear($start,$end){
        $s = date('Y',strtotime($start));
        $e = date('Y',strtotime($end));
        return (int)$e-(int)$s;
    }

    /**
     * 返回百度坐标
     * @name: baiduMap
     * @param $addr
     * @return false|string
     * @throws \yii\base\InvalidConfigException
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/28 1:37 下午
     */
    public static function baiduMap($addr) {
        //0CNPLC3fihOBzYRtznNFUFDdnoojhFDQ
        //http://api.map.baidu.com/geocoding/v3/?address=北京市海淀区上地十街10号&output=json&ak=您的ak&callback=showLocation
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl('http://api.map.baidu.com/geocoding/v3/')
            ->setData([
                'address' => $addr,
                'output' => 'json',
                'ak' => '0CNPLC3fihOBzYRtznNFUFDdnoojhFDQ',
            ])
            ->send();
        if ($response->isOk) {
            $data = $response->data;
            if ($data['status'] == 0) {
                return json_encode($data['result']['location']);
            }
        }
        return '';
    }
}
