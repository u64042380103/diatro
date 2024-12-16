<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DormitoryLease extends Model
{
    protected $table = 'dormitory_lease_agreement';
    protected $fillable = [
        'user_id', 'rooms_id', 'startDate', 'endDate', 'monthly_rent', 'deposit_amount', 'status'
    ];

    public function user_name()
    {
        return $this->belongsTo(DormitoryUser::class, 'user_id');
    }
    public function username()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->where('status_delete', '!=', 'Disable');
    }
    
}

class DormitoryUser extends Model
{
    protected $table = 'dormitory_users';

    public function rooms()
    {
        return $this->belongsTo(DormitoryRoom::class, 'room_id')->where('status_delete', '!=', 'Disable');
    }
}

// class DormitoryRoom extends Model
// {
//     protected $table = 'dormitory_rooms';

//     public function dormitory()
//     {
//         return $this->belongsTo(Dormitory::class, 'dormitorys_id');
//     }
// }
