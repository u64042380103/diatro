<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DormitoryDetails extends Model
{
    use HasFactory;

    protected $table = 'dormitory_rooms_details';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }

    public function rooms()
    {
        return $this->hasMany(DormitoryRoom::class, 'room_id', 'id')->where('status_delete', '!=', 'Disable');
    }
    public function Repairs()
    {
        return $this->hasMany(DormitoryRepair::class, 'tool_id', 'id')->where('status_delete', '!=', 'Disable');
    // $Repairs = DormitoryRepair::where('tool_id', $item->id)->where('status_delete', '!=', 'Disable')->get();
    }
}