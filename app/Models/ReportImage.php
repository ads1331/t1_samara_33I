<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['report_id', 'image_path', 'page_number'];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
    
}
