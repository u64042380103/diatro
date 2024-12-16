<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\UserGroup\Entities\ModelUserGroup;
use Modules\User\Entities\Modules_User_Lavel;
use Modules\User\Entities\Modules_User;

class Modules_User_Lavel extends Model
{
    use HasFactory;

    protected $table = 'user_Level';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ControlSystem\Database\factories\ModelControlSystemFactory::new();
    }

    public function user_groups()
    {
        return $this->hasOne(ModelUserGroup::class, 'id', 'group_id')->where('status_delete', '!=', 'Disable');
    }
    public function user()
    {
        return $this->hasOne(Modules_User::class, 'id', 'user_id')->where('status_delete', '!=', 'Disable');
    }
}