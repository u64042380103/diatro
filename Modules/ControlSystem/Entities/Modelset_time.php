<?php

namespace Modules\ControlSystem\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modelset_time extends Model
{
    use HasFactory;

    protected $table = 'set_time';
    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ControlSystem\Database\factories\ModelControlSystemFactory::new();
    }
}
