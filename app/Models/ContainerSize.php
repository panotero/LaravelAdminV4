<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContainerSize extends Model
{
    protected $table = 'container_size';
    protected $fillable = ['size'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s A',
        'updated_at' => 'datetime:Y-m-d h:i:s A',
    ];
}
