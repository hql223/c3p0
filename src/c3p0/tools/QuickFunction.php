<?php

namespace c3p0\tools;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class QuickFunction {

    // 输出提示信息
    public static function alert($message = '', $url = '') {
        header('content-type:text/html;charset=utf-8');
        if (!empty($message)) {
            if (!empty($url)) {
                echo "<script type='text/javascript'>alert('$message');window.location.href='$url';</script>";
            } else {
                echo "<script type='text/javascript'>alert('$message');history.go(-1);</script>";
            }
        }
        exit;
    }

    // 写入日志文件
    public static function L($logfile = '', $info = '', $group = 'default') {
        $log = new Logger($group);
        $handler = new StreamHandler($logfile, Logger::INFO);
        $log->pushHandler($handler);
        $log->info($info);
    }

    // 缓存操作函数
    public static function C($key = '', $value = '', $group = 'default', $expire = 60, $which = 'default') {
        global $_cache;
        if (empty($value))
            return $_cache[$which]->load($key);
        $_cache[$which]->save($value, $key, array($group), $expire);
    }

}
