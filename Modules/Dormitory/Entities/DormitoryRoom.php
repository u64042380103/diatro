<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Dormitory\Entities\DormitoryLease;

use Carbon\Carbon;

class DormitoryRoom extends Model
{
    use HasFactory;
    protected $table = 'dormitory_rooms';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryRoomFactory::new();
    }
    public function dormitorys()
    {
        return $this->hasOne(Dormitory::class, 'id', 'dormitorys_id')->where('status_delete', '!=', 'Disable');
    }
    public function residents()
    {
        return $this->hasMany(DormitoryUser::class, 'room_id');
    }

    public function metersLatest()
    {
        return $this->hasOne(DormitoryMeter::class, 'rooms_id', 'id')->latest();
    }

    public function metersPreviouslast()
    {
        $date = Carbon::now();
        return $this->hasOne(DormitoryMeter::class, 'rooms_id', 'id')
        ->whereMonth('created_at', $date->subMonth());
    }
    
    public function previousMonthMeter()
    {
        $latestMeter = $this->metersLatest()->first(); // Get the latest meter
        if ($latestMeter) {
            $previousMonth = Carbon::parse($latestMeter->created_at)->subMonth(); // Determine the previous month
            return $this->hasOne(DormitoryMeter::class, 'rooms_id', 'id')
                        ->whereYear('created_at', $previousMonth->year)
                        ->whereMonth('created_at', $previousMonth->month);
        }
        return null;
    }
    
    public function metersWater() {
        return $this->hasMany(DormitoryMeter::class, 'rooms_id', 'id')->where('type', 'water');
    }

    public function metersElectric() {
        return $this->hasMany(DormitoryMeter::class, 'rooms_id', 'id')->where('type', 'electric');
    }

    public function latestLease()
    {
        return $this->hasMany(DormitoryLease::class, 'rooms_id', 'id')->where('status_delete', '!=', 'Disable')->orderby('id','asc');
    }
    public function billings()
    {
    return $this->hasOne(DormitoryBillings::class, 'room_id', 'id')->where('status_delete', '!=', 'Disable')->latest();
    }
    public function billingsss()
    {
    return $this->hasMany(DormitoryBillings::class, 'room_id', 'id')
        ->where('status_delete', '!=', 'Disable');
    }

    public function Lease()
    {
    return $this->hasOne(DormitoryLease::class, 'room_id', 'id')->where('status_delete', '!=', 'Disable')->orderby('id','asc')->latest();
    }
    public function Monthlys_rent()
    {
        return $this->hasMany(DormitoryMonthly_rent::class, 'room_id', 'id')->where('status_delete', '!=', 'Disable')->orderby('id','asc');
    }
    public function Monthly_rent()
    {
    return $this->hasOne(DormitoryMonthly_rent::class, 'room_id', 'id')->where('status_delete', '!=', 'Disable')->orderby('id','asc')->latest();
    }

}