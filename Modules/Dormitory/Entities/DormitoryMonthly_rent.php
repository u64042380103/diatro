<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DormitoryMonthly_rent extends Model
{
    use HasFactory;

    protected $table = 'dormitory_monthly_rent';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }

    public function rooms()
    {
        return $this->hasMany(DormitoryRoom::class, 'room_id', 'id')->where('status_delete', '!=', 'Disable');
    }
    
}
