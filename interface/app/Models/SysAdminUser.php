<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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


}
