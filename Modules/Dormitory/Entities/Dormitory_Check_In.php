<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Modules_User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Dormitory_Check_In extends Model
{
    use HasFactory;

    protected $table = 'dormitory_check_in';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }

    public function rooms()
    {
        return $this->hasOne(DormitoryRoom::class, 'id', 'room_id')->where('status_delete', '!=', 'Disable');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id')
        ->where('status_delete', '!=', 'Disable')
        ;
    }
    
}
