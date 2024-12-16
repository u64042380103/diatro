<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dormitory\Entities\DormitoryUser;
use Modules\Dormitory\Entities\DormitoryRoom;

class DormitoryBillings_month extends Model
{
    use HasFactory;

    protected $table = 'dormitory_billings_monthly';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }
    
    public function rooms()
    {
        return $this->hasOne(DormitoryRoom::class, 'id', 'room_id')->where('status_delete', '!=', 'Disable');
    }
    

}