<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Modules\UserGroup\Entities\ModelUserGroup;

class Modules_User extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ControlSystem\Database\factories\ModelControlSystemFactory::new();
    }
    public function user_group()
    {
        return $this->hasOne(ModelUserGroup::class, 'id', 'user_type')->where('status_delete', '!=', 'Disable');
    }

    // public function getsettime() {
    //     return $this->hasMany(Modelset_time::class, 'device_id', 'device_id');
    // }
}