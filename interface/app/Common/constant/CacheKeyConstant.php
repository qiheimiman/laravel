<?php
/**
 * Created by PhpStorm.
 * User: Host-0034
 * Date: 2018/7/20
 * Time: 15:24
 */

namespace app\common\constant;


class CacheKeyConstant
{

    // 管理员 登录 token key
    const ADMIN_LOGIN_KEY = "admin:login:";
    const ADMIN_LOGIN_KEY_MERCHANT = "admin_merchant:login:";

    // App 登录 token key
    const APP_LOGIN_KEY = 'app:login:';
    const APP_LOGIN_KEY_MERCHANT = 'app_merchant:login:';
    const PC_LOGIN_KEY = 'pc:login:';

    const APP_LOGIN_VERIFYCODE_KEY = 'verifycode:login:';
    const APP_LOGIN_VERIFYCODE_KEY_MERCHANT = 'verifycode_merchant:login:';

    const APP_REGISTER_VERIFYCODE_KEY = 'verifycode:register:';
    const APP_REGISTER_VERIFYCODE_KEY_MERCHANT = 'verifycode_merchant:register:';

    const APP_RESETPWD_VERIFYCODE_KEY = 'verifycode:resetpassword:';
    const APP_RESETPWD_VERIFYCODE_KEY_MERCHANT = 'verifycode_merchant:resetpassword:';

    const APP_BINDPHONE_VERIFYCODE_KEY = 'verifycode:bindPhone:';
    const APP_BINDPHONE_VERIFYCODE_KEY_MERCHANT = 'verifycode_merchant:bindPhone:';

}