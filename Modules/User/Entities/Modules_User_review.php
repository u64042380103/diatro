<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Entities\Modules_User;
use Modules\Dormitory\Entities\Dormitory;

class Modules_User_review extends Model
{
    use HasFactory;

    protected $table = 'users_review';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ControlSystem\Database\factories\ModelControlSystemFactory::new();
    }

    public function user()
    {
        return $this->hasOne(Modules_User::class, 'id', 'users_id')->where('status_delete', '!=', 'Disable');
    }

    public function dormitory()
    {
        return $this->hasOne(Dormitory::class, 'id', 'dormitorys_id')->where('status_delete', '!=', 'Disable');
    }
}