<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryRoom;

class DormitoryBillings extends Model
{
    use HasFactory;

    protected $table = 'dormitory_billings';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(DormitoryUser::class, 'user_id')->where('status_delete', '!=', 'Disable');
    }
    
    public function rooms()
    {
        return $this->belongsTo(DormitoryRoom::class, 'room_id')->where('status_delete', '!=', 'Disable');
    }
    // public function rooms()
    // {
    //     return $this->hasOne(DormitoryRoom::class, 'id', 'room_id')->where('status_delete', '!=', 'Disable');
    // }

    public function Monthly_rents()
    {
    return $this->hasMany(DormitoryBillings_month::class, 'billings_id', 'id')
        ->where('status_delete', '!=', 'Disable')
        ->orderBy('id', 'asc');
    }

    
}