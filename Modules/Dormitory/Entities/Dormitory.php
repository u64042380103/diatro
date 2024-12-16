<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Modules_User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dormitory extends Model
{
    use HasFactory;

    protected $table = 'dormitorys';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }

    public function rooms()
    {
        return $this->hasMany(DormitoryRoom::class, 'dormitorys_id', 'id')->where('status_delete', '!=', 'Disable');
    }
    public function recorder() {
        return $this->hasOne(Modules_User::class, 'id', 'recorder_id')->where('status_delete', '!=', 'Disable');
    }
    
}
