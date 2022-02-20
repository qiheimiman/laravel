<?php
namespace App\Admin\Models\System;


use Illuminate\Database\Eloquent\Model;

/**
 * 权限授权表
 */
class AdminPermission extends Model
{
    protected $table = 'sys_admin_permission';
    protected $primaryKey = 'permission_id';

    protected $dateFormat = 'Y-m-d H:i:s';

    public static function getNodeByName($name)
    {
        return self::query()->where('name',$name)->find();
    }


    public static function getParents( $id ){
        $model = self::query()->find($id);
        $list = [];
        if( $model){
            $list[] = $model['permission_id'];
            $parent = self::getParents( $model[ "pid"]);
            $list = array_merge( $list, $parent);
        }
        return array_filter( $list);
    }

    public static function getTitleById($id)
    {

        $res = self::query()->where('permission_id',$id)->value('permission_title');

        return $res ? : '';
    }
    public static function getPidById($id)
    {
        $res = self::query()->where('permission_id',$id)->value('pid');
        return $res ? : false;
    }
}

