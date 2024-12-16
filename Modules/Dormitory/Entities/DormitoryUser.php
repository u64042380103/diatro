<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dormitory\Entities\DormitoryRoom;
use App\Models\User;

class DormitoryUser extends Model
{
    use HasFactory;

    protected $table = 'dormitory_users';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }

    // DormitoryUser.php (Model)
// public function rooms()
// {
//     return $this->hasMany(DormitoryRoom::class, 'dormitory_user_id', 'id');
// }

public function rooms() {
    return $this->hasOne(DormitoryRoom::class, 'id', 'room_id')->where('status_delete', '!=', 'Disable');
}

// public function data_rooms() {
//     return $this->hasMany(DormitoryRoom::class, 'id', 'room_id')->where('status_delete', '!=', 'Disable');
// }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'users_id')->where('status_delete', '!=', 'Disable');
    }
}
