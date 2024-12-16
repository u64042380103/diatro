<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\User\Entities\Modules_User;
use Modules\Dormitory\Entities\Dormitory;

class DormitoryComment extends Model
{
    use HasFactory;

    protected $table = 'dormitory_comment';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ControlSystem\Database\factories\ModelControlSystemFactory::new();
    }
    public function recorder() {
        return $this->hasOne(Modules_User::class, 'id', 'recorder_id')->where('status_delete', '!=', 'Disable');
    }

}