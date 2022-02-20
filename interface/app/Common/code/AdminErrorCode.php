<?php

namespace App\Common\code;

/**
 * 后台系统错误码
 * Class ErrorCode
 * @package app\common\model
 */
class AdminErrorCode
{

    // +----------------------------------------------------------------------
    // | 系统级错误码
    // +----------------------------------------------------------------------
    const NOT_NETWORK = ['code' => 1, 'message' => '系统繁忙，请稍后再试。'];

    // +----------------------------------------------------------------------
    // | 服务级错误码
    // +----------------------------------------------------------------------
    const LOGIN_FAILED = ['code' => 2, 'message' => '登录失效'];
    const HTTP_METHOD_NOT_ALLOWED = ['code' => 3, 'message' => '网络请求不予许'];
    const VALIDATION_FAILED = ['code' => 4, 'message' => '身份验证失败'];
    const USER_AUTH_FAIL = ['code' => 5, 'message' => '用户名或者密码错误'];
    const USER_NOT_PERMISSION = ['code' => 6, 'message' => '当前没有权限登录'];
    const AUTH_FAILED = ['code' => 7, 'message' => '权限验证失败'];
    const DATA_CHANGE = ['code' => 8, 'message' => '数据没有任何更改'];
    const DATA_REPEAT = ['code' => 9, 'message' => '数据重复'];
    const DATA_NOT = ['code' => 10, 'message' => '数据不存在'];
    const DATA_VALIDATE_FAIL = ['code' => 11, 'message' => '数据验证失败'];
    const DATA_SUBMIT_FAIL = ['code' => 12, 'message' => '提交失败'];
    const DATA_YES_NO = ['code' => 13, 'message' => '此地区已有管理机构！'];

    const DATA_BUZU_FAIL= [ 'code' => 96, 'message' => '优惠券不足，请重新填写数量' ];
    const DATA_USERO_FAIL= [ 'code' => 95, 'message' => '所选机构与用户不匹配！' ];


    const VCODE_USER_NO= [ 'code' => 99, 'message' => '此用户已申请过该项目，不能重复申请！' ];
    const DATA_NO_DJQ= [ 'code' => 98, 'message' => '该项目已无代金券可使用！' ];
    const DATA_CAED_FAIL= [ 'code' => 97, 'message' => '身份证已存在！' ];
}
