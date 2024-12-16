<?php

namespace Modules\ApiDevice\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Modules\ApiDevice\Entities\ApiDeviceModules;

class ApiDeviceModules extends Model
{
    use HasFactory;

    protected $table = 'controlsystems';
    protected $fillable = [];
    

}
