<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/7/20
 * Time: 15:30
 */

namespace App\Common\utils;

/*
 * 密码相关 工具类
 */
class PassWordUtils
{

    /**
     * 生成密码
     * @param $v
     * @return string
     */
    public static function create($v, $sign = '')
    {

        return md5(md5($v).$sign);
    }

}
