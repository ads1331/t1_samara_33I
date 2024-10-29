<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'start_date',
        'duration',
        'progress',
        'parent',
        'sortorder',
    ];

    protected $appends = ['open'];

    public function getOpenAttribute()
    {
        return true;
    }
}
