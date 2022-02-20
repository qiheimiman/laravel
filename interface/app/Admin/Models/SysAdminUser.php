<?php

namespace App\Admin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Common\constant\CacheKeyConstant;
use App\common\utils\TokenUtils;
use Illuminate\Support\Facades\Cache;

use App\Admin\Models\System\AdminRoleAdmin;
use App\Admin\Models\System\AdminRole;
class SysAdminUser extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sys_admin_user';
    protected $primaryKey = 'id';
    protected $dateFormat = 'Y-m-d H:i:s';

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    const DELETED_AT = 'delete_time';

    /**
     * Prepare a date for array / JSON serialization.
     * 格式化时间
     */
    protected function serializeDate(\DateTimeInterface $date) : string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function loginInfo($id, $values){
        $key = CacheKeyConstant::ADMIN_LOGIN_KEY . $id;
        if ($values && is_array($values)){
            $values['token'] = TokenUtils::create("admin" . $id);
            Cache::put($key, $values);
            $values = $values['token'];
        }
        $info = Cache::get($key);
        if (!empty($info['id']) && !empty($info['token']) && $info['token'] == $values){
            if($values && is_string($values)){
                if($info['token'] != $values)
                    return false;
            }
            return $info;
        }
        return false;
    }

    // 关联角色表
    public function roles()
    {
        return $this->belongsToMany(AdminRole::class,  AdminRoleAdmin::class, 'role_id', 'admin_id');
    }
}
