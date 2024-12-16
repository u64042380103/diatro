<?php

namespace Modules\Dormitory\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\Modules_User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dormitory_Facilitate extends Model
{
    use HasFactory;

    protected $table = 'dormitory_Facilitate';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Dormitory\Database\factories\DormitoryFactory::new();
    }

    
}
