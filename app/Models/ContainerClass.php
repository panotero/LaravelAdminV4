<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerClass extends Model
{
    protected $table = 'container_class';
    protected $fillable = ['class'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s A',
        'updated_at' => 'datetime:Y-m-d h:i:s A',
    ];
}
