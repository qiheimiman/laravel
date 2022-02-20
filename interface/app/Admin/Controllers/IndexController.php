<?php


namespace App\Admin\Controllers;


use App\Admin\Controllers\System\BaseController;
use App\Admin\Validate\IndexValidate;
use App\Models\SysAdminUser;
use App\Common\response\Response;
use App\Common\code\AdminErrorCode;
class IndexController extends BaseController
{

    public function index(){
        $data = ['ida'=>'a'];
        $u = SysAdminUser::query()->first()->toArray();
//        return Response::error(AdminErrorCode::DATA_SUBMIT_FAIL);

        $validate = new IndexValidate();
        if( !$validate->scene('index')->check($data)){

            pd( $validate->getError());

            return false;
        };
        return 'ab';
    }

}
