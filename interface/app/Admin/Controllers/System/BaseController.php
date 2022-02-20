<?php

namespace App\Admin\Controllers\System;

use App\Http\Controllers\Controller;

use App\Admin\Middleware\AdminAuth;
use App\Admin\Models\SysAdminUser as AdminUser;
// 管理员基础控制器
class BaseController extends Controller
{


    // 无需登录的方法
    protected $noNeedLogin = [];

    // 无需使用 AdminLog 记录后台日志的方法
    protected $noNeedLogger = [];

    // 当前模型
    protected $model = null;

    // where条件
    protected $map = [];

    // search条件
    protected $search = [
        ['create_time', 'between', 'create_time'],
        ['status', '=', 'status'],
        ['title', 'like', 'keywords']
    ];

    // 模型关联
    protected $with = [];

    // 排序
    protected $order = 'create_time desc';

    // 处理数据, 传入方法名
    protected $process = null;

    protected $paginate = [];

    // CURD方法
//    use \app\admin\traits\Curd;
    public function initialize()
    {

        #当前应用名
        $this->app = app('http')->getName();

        // 设置默认model
        if ($this->model == null) {
            $this->model = 'app\\common\\model\\sys\\' . request()->controller();
        }

        $this->post  = request()->param();
        $this->paginate = [
            'type' => 'bootstrap',
            'var_page' => 'page',
            'list_rows' => (isset($this->post['limit']) && $this->post['limit']) ? (int)$this->post['limit'] : 20
        ];

    }

    protected function getAdmin(){
        $id = request()->header('X-AdminId');
        $token = request()->header('X-AdminToken');

        if (!$id || !$token) {
            return false;
        }
        $loginInfo = AdminUser::loginInfo($id, (string)$token);
        if( $loginInfo != false){
            $admin_info = AdminUser::query()->find( $id);
            return $admin_info->toArray();
        }
        return null;
    }

    /**
     * 使用即定验证器，验证Post请求数据
     * @param  [type] $rules [description]
     * @return [type]        [description]
     */
    public function validatePost( $rules = [], $msg = []){
        $data = request()->post();
        return $this->validateData( $data, $rules, $msg);
    }

    public function validateRequest( $rules = [], $msg = []){
        $data = request()->param();
        return $this->validateData( $data, $rules, $msg);
    }


    /**
     * 使用验证器，验证任何一数据
     * @param  [type] $data  [description]
     * @param  [type] $rules [description]
     * @param  array  $msg   [description]
     * @return [type]        [description]
     */
    public function validateData( $data, $rules, $msg = []){
        $msg = $this->validate( $data, $rules, $msg);

        if( $msg !== true){
            $e = new \app\common\exception\ParamException(
                ['msg'=> $msg, "code" => 500 ]
            );
            throw $e;
        }
        return $data;
    }

    /**
     * @Notes: 自动调用参数 验证器
     * @remarks： 控制器:controller/v1/Login.php 方法名:login 对应的验证器为 validate/v1/LoginValidate.php
     * @Interface commonValiadate
     * @param string $ValidateClassName 验证器的类名 默认取名规则：当前控制器类名 + Validate (非必须)
     * @return \think\response\Json
     * @author: xiaojinghui
     * @Time: 2021/11/3   9:30
     */
    protected function commonValiadate($ValidateClassName = ''){
        //没有传验证器名称 则取 当前控制器类名 + Validate
        $ValidateClassName = !empty($ValidateClassName) ? $ValidateClassName :
            str_replace('.','\\',Request::controller()). 'Validate';

        //验证 当前请求控制器 当前方法 的参数
        validate('app\\' .   app('http')->getName() . '\\validate\\' . $ValidateClassName)
            ->scene(Request::action())->check($this->request->param());
//        catch (ValidateException $e) {
//            throw new \think\exception\ValidateException(Response::error(ApiErrorCode::DATA_CHANGE));
//
//        }
//        catch (\Exception $e){
//            return Response::error(ApiErrorCode::DATA_VALIDATE_FAIL);
//        }

    }

}
