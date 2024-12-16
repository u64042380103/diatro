<?php

namespace Modules\ControlSystem\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Modules\ControlSystem\Entities\Modelset_time;

class ModelControlSystem extends Model
{
    use HasFactory;

    protected $table = 'controlsystems';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ControlSystem\Database\factories\ModelControlSystemFactory::new();
    }

    // public function getsettime() {
    //     return $this->hasMany(Modelset_time::class, 'device_id', 'device_id');
    // }
}
