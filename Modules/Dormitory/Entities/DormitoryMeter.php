<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DormitoryMeter extends Model
{
    use HasFactory;

    protected $table = 'dormitory_meters';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new()->where('status_delete', '!=', 'Disable');
    }

    public function roomss()
    {
        return $this->hasOne(DormitoryRoom::class, 'id', 'rooms_id')->where('status_delete', '!=', 'Disable');
    }
//     public function residents()
// {
//     return $this->hasMany(DormitoryRoom::class, 'id');
// }
}