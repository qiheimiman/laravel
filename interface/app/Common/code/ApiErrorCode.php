<?php
namespace App\Common\code;

/**
 * 前台系统错误码
 * Class ErrorCode
 * @package app\common\model
 */
class ApiErrorCode
{
    // +----------------------------------------------------------------------
    // | 系统级错误码
    // +----------------------------------------------------------------------
    const NOT_NETWORK = [ 'code' => 1, 'message' => '系统繁忙，请稍后再试。'];

    // +----------------------------------------------------------------------
    // | 服务级错误码
    // +----------------------------------------------------------------------
    const LOGIN_FAILED = [ 'code' => 2, 'message' => '登录失效'];
    const HTTP_METHOD_NOT_ALLOWED = [ 'code' => 3, 'message' => '网络请求不予许'];
    const VALIDATION_FAILED = [ 'code' => 4, 'message' => '身份验证失败'];
    const USER_AUTH_FAIL = [ 'code' => 5, 'message' => '用户名或者密码错误'];
    const USER_NOT_PERMISSION = [ 'code' => 6, 'message' => '当前没有权限登录'];
    const AUTH_FAILED = [ 'code' => 7, 'message' => '权限验证失败'];
    const DATA_CHANGE = [ 'code' => 8, 'message' => '数据没有任何更改'];
    const DATA_REPEAT = [ 'code' => 9, 'message' => '数据重复'];
    const DATA_NOT = [ 'code' => 10, 'message' => '数据不存在'];
    const DATA_VALIDATE_FAIL = [ 'code' => 11, 'message' => '数据验证失败'];
    const DATA_SUBMIT_FAIL = [ 'code' => 12, 'message' => '提交失败'];
    const DATA_SIGN_FAIL = [ 'code' => 13, 'message' => '签名错误' ];
    const DATA_ACCOUNT_NO= [ 'code' => 14, 'message' => '该账号已经被禁用' ];
    const DATA_INFO_NO= [ 'code' => 15, 'message' => '该用户已提交过该项目' ];

    const MOBILE_IS_EXIST= [ 'code' => 28, 'message' => '手机号已存在' ];
    const MOBILE_NO_REGISTER= [ 'code' => 29, 'message' => '手机号未注册' ];
    const VCODE_ERROR= [ 'code' => 30, 'message' => '验证码错误' ];
    const VCODE_BE_OVERDUE= [ 'code' => 31, 'message' => '验证码已过期' ];


    const VCODE_USER_NO= [ 'code' => 99, 'message' => '登录失败！' ];
    const DATA_NO_DJQ= [ 'code' => 98, 'message' => '账号不存在！' ];
    const DATA_CAED_FAIL= [ 'code' => 97, 'message' => '账号被禁用！'];
    const DATA_BOUND= [ 'code' => 96, 'message' => '该账号已绑定其他微信！'];

    const DATA_IMG_NO= [ 'code' => 95, 'message' => '图片不能为空！'];
    const DATA_IMG_BEYOND= [ 'code' => 94, 'message' => '图片数量超出限制！'];
    const DATA_IMG_FAILED= [ 'code' => 93, 'message' => '发布失败！'];

    const DATA_VIDEO_NO= [ 'code' => 92, 'message' => '视频不能为空！'];
    const DATA_VIDEO_IMG= [ 'code' => 91, 'message' => '视频封面不能为空！'];
    const DATA_VIDEO_NUM= [ 'code' => 90, 'message' => '视频数量超出限制！'];

    const DATA_AUDIO_NO= [ 'code' => 89, 'message' => '音频不能为空！'];
    const DATA_AUDIO_NUM= [ 'code' => 88, 'message' => '音频数量超出限制！'];

}
