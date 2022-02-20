<?php

namespace App\Admin\Controllers\System;


use App\Admin\Models\System\AdminPermission;
use App\Common\response\Response;
use App\Common\code\AdminErrorCode;
use App\Admin\Models\SysAdminUser as AdminUser;
use App\Common\utils\PassWordUtils;
use Illuminate\Http\Request;
use App\Common\utils\PublicFileUtils;

class LoginController extends BaseController
{

    protected $adminId = null;
    protected $adminToken = null;

    public function initialize()
    {
        parent::initialize();



    }

    public function __construct()
    {

        $this->adminId = request()->header('X-AdminId');
        $this->adminToken = request()->header('X-AdminToken');

    }

    // 登陆接口
    public function index(Request $request)
    {

        $user_name = $request->post('username');
        $pwd = $request->post('password');
        if (!$user_name || !$pwd) {
            return Response::error(AdminErrorCode::VALIDATION_FAILED, "username 不能为空。 password 不能为空。");
        }
        $admin = AdminUser::query()->select('id','username','avatar','password','status')
            ->where('username', $user_name)
            ->first();


        if ( !$admin || ( PassWordUtils::create($pwd) != $admin->password) ) {
            return Response::error(AdminErrorCode::USER_AUTH_FAIL );
        }
        if ($admin->status != 1) {
            return Response::error(AdminErrorCode::USER_NOT_PERMISSION);
        }

        $info = $admin->toArray();
        unset($info['password']);

        // 获取用户权限
        $permission = $this->getPermission($info['id']);

        // 权限列表
        $info['auth'] = isset($permission['permission']) ? $permission['permission'] : [];
        // 路由列表
        $info['routes'] = isset($permission['routes']) ? $permission['routes'] : [];
        // 角色列表
        $info['roles'] = isset($permission['roles']) ? $permission['roles'] : [];


        // 获取登录信息
        $loginInfo = AdminUser::loginInfo($info['id'], $info);
        $admin->last_login_ip = request()->ip();
        $admin->last_login_time = date("Y-m-d H:i:s");
        $admin->save();

        $res = [];
        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';


        //记录登陆日志
//        $agent = new Agent();
//        $browser = $agent->browser();
//        $platform = $agent->platform();
//        $version = $agent->version($platform);
//        SysSystemLog::create([
//            'uid'=> $loginInfo['id'],
//            'title'=> $loginInfo['username'],
//            'url'=> request()->url(true),
//            'value'=> [],
//            'ip'=> request()->ip(),
//            'remark'=> '登陆',
//            'create_time'=> times(),
//            'browser_info'=> $platform.'/'.$version.'/'.$browser,
//            'type' => 1
//        ]);

        return Response::success($res);
    }

    /**
     * 获取登录用户信息
     */
    public function userInfo()
    {

        if (!$this->adminId || !$this->adminToken) {
            return Response::error(AdminErrorCode::LOGIN_FAILED);
        }


        $res = AdminUser::loginInfo($this->adminId, (string)$this->adminToken);

        if (empty($res["id"])) {
            return Response::error(AdminErrorCode::LOGIN_FAILED);
        }

        $res['id'] = !empty($res['id']) ? intval($res['id']) : 0;
        $res['avatar'] = !empty($res['avatar']) ? PublicFileUtils::createUploadUrl($res['avatar']) : '';
        return Response::success($res);
    }

    public function relogin()
    {
        if (!$this->adminId || !$this->adminToken) {
            return Response::error(AdminErrorCode::LOGIN_FAILED);
        }
        $info = AdminUser::where('id', $this->adminId)
            ->field('id,username,avatar,status')
            ->find()->toArray();

        // 获取用户权限
        $permission = $this->getPermission($info['id']);
        // 权限列表
        $info['auth'] = $permission['permission'];
        // 路由列表
        $info['routes'] = $permission['routes'];
        // 清除登录信息
        AdminUser::loginOut($this->adminId);
        // 重新获取登录信息
        $loginInfo = AdminUser::loginInfo($info['id'], $info);

        $res = [];
        $res['id'] = !empty($loginInfo['id']) ? intval($loginInfo['id']) : 0;
        $res['token'] = !empty($loginInfo['token']) ? $loginInfo['token'] : '';
        return Response::success($res);
    }

    /**
     * 退出
     */
    public function out()
    {

        if (!request()->isPost()) {

            return Response::error(AdminErrorCode::HTTP_METHOD_NOT_ALLOWED);
        }


        if (!$this->adminId || !$this->adminToken) {
            return Response::error(AdminErrorCode::LOGIN_FAILED);
        }
        $loginInfo = AdminUser::loginInfo($this->adminId, (string)$this->adminToken);


        if ($loginInfo == false) {
            return Response::error(AdminErrorCode::LOGIN_FAILED);
        }

        AdminUser::loginOut($this->adminId);

        //记录登陆日志
        $agent = new Agent();
        $browser = $agent->browser();
        $platform = $agent->platform();
        $version = $agent->version($platform);
        SysSystemLog::create([
            'uid'=> $loginInfo['id'],
            'title'=> $loginInfo['username'],
            'url'=> request()->url(true),
            'value'=> [],
            'ip'=> request()->ip(),
            'remark'=> '登出',
            'create_time'=> times(),
            'browser_info'=> $platform.'/'.$version.'/'.$browser,
            'type' => 2
        ]);


        return Response::success();
    }

    /**
     * 获取用户权限表
     * @author Breeze
     * @param integer $id
     * @return array
     */
    protected function getPermission($id)
    {

        $res = AdminUser::query()->with(['roles'])->find($id);


        $data = [];
        $data = $roles = $res->roles;

        $auth = [];

        foreach ($res->roles as $key => $val) {
            $auth[] = $val['auth'];
        }


        $auth = implode(',', $auth);
        $auth = explode(',', $auth);
        $permission_ids = array_unique($auth);

        $permission = new AdminPermission();

        if (in_array('*', $permission_ids)) {
            $res = $permission::query()->where([['status', '>', '0']])
                ->orderBy('sort','desc')->orderBy('permission_id','asc')->get();
        } else {
            $res = $permission::query()
                ->where([['status', '>', '0'], ['permission_id', 'IN', $permission_ids]])
                ->orderBy('sort', 'desc')
                ->orderBy('permission_id','asc')->get();
        }



        $data = $res->toArray();

        $data = self::infiniteTree($data);
        $data['routes'] = self::setRedirect($data['routes']);
        $data['roles'] = $roles;
        return $data;
    }

    // 处理加载组件和跳转地址
    private static function setRedirect($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (!empty($val['children'])) {
                    // 设置重定向地址
                    if (!isset($val['router_redirect'])) {
                        $data[$key]['redirect'] = $val['children'][0]['component'];
                    }
                    // 设置组件路径
                    if ($val['component'] != 'Layout') {
                        if (!isset($val['router_component'])) {
                            $data[$key]['component'] = 'LayoutChild';
                        }
                    }
                    $data[$key]['children'] = self::setRedirect($val['children']);
                }
            }
        }
        return $data;
    }

    /**
     * 生成路由数据和权限列表
     * @author Breeze
     * @param array $data
     * @param integer $pid
     * @param string $path
     * @return array
     */
    private static function infiniteTree($data, $pid = 0, $path = '')
    {
        if (!is_array($data) || empty($data)) {
            return false;
        }
        $routes = [];
        static $permission = [];
        foreach ($data as $key => $val) {
            // 顶级菜单
            if ($pid == 0) {
                $auth = $name = '/' . trim($val['name'], '/');
                $defaultComponent = 'Layout';
                $noBreadcrumb = true;
            } else {
                // 非顶级菜单
                $name = trim($val['name'], '/');
                $auth = $path . '/' . $name;
            }
            $temp = [];
            // 处理子级菜单
            if ($val['pid'] == $pid) {
                $temp['path'] = $name;
                if (!empty($val['router_query'])) {
                    $query = trim($val['router_query'], '/');
                    $temp['path'] = $name . '/' . $query;
                }
                $temp['component'] = isset($defaultComponent) ? $defaultComponent : $auth;
                $temp['name'] = self::convertHump($auth);
                $meta = ['title' => $val['permission_title'], 'permission_id' => $val['permission_id']];
                // 设置图标
                if (isset($val['router_icon']) && $val['router_icon']) {
                    $meta['icon'] = $val['router_icon'];
                }
                $temp['meta'] = $meta;
                // 顶级路由状态为1添加总是显示
                if ($val['router_status'] == 1 && $val['pid'] == 0) {
                    $temp['alwaysShow'] = true;
                }
                // 非顶级路由状态为2隐藏路由
                if ($val['router_status'] == 2 && $val['pid'] != 0) {
                    $temp['hidden'] = true;
                }
                // 面包屑中不显示顶级路由
                if (isset($noBreadcrumb) && $noBreadcrumb) {
                    $temp['meta']['breadcrumb'] = false;
                }
                // 自动设置高亮菜单
                if ($val['name'] == 'add' || $val['name'] == 'edit' || $val['name'] == 'show') {
                    $temp['meta']['activeMenu'] = $path . '/index';
                }
                // 手动设置高亮菜单
                if (isset($val['active_menu']) && $val['active_menu']) {
                    $temp['meta']['activeMenu'] = $val['active_menu'];
                }
                // 设置重定向地址
                if (isset($val['router_redirect']) && $val['router_redirect']) {
                    $temp['redirect'] = $val['router_redirect'];
                }
                $children = self::infiniteTree($data, $val['permission_id'], $auth);
                if (isset($children['routes']) && !empty($children['routes'])) {
                    $temp['children'] = $children['routes'];
                }
                // 路由状态为0不添加路由
                if ($val['router_status'] != 0) {
                    $routes[] = $temp;
                }
                $permission[] = $auth;
                // 删除遍历过的数组数据
                unset($data[$key]);
            }
        }
        return [
            'routes' => $routes,
            'permission' => $permission
        ];
    }

    /**
     * 返回驼峰名称
     * @author Breeze
     * @param string $str
     * @return string
     */
    private static function convertHump($str)
    {
        $arr = explode('/', $str);
        for ($i = 1; $i < count($arr); $i++) {
            $arr[$i] = ucfirst($arr[$i]);
        }
        $str = implode('', $arr);
        return $str;
    }
}
