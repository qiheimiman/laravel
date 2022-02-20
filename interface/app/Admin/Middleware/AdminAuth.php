<?php

namespace App\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Common\response\Response;
use App\Common\code\AdminErrorCode;
use App\Admin\Models\SysAdminUser;
class AdminAuth
{
    public function handle($request, \Closure $next)
    {
        $id = $request->header('X-AdminId');
        $token = $request->header('X-AdminToken');

        if (!$id || !$token) {
            return Response::error(AdminErrorCode::LOGIN_FAILED,'缺少token、id');
        }


        $loginInfo = SysAdminUser::loginInfo($id, (string)$token);
        if ($loginInfo == false) {
            return Response::error(AdminErrorCode::LOGIN_FAILED);
        }
        return $next($request);
    }
}
