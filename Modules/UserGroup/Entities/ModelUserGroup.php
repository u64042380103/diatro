<?php

namespace Modules\UserGroup\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModelUserGroup extends Model
{
    use HasFactory;

    protected $table = 'user_groups';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\UserGroup\Database\factories\ModelUserGroupFactory::new();
    }
}
