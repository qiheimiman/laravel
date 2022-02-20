<?php
namespace App\Admin\Models\System;

use Illuminate\Database\Eloquent\Model;

/**
 * 角色表
 */
class AdminRole extends Model
{
    protected $table = 'sys_admin_role';
    protected $primaryKey = 'role_id';
}
