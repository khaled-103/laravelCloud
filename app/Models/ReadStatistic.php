<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReadStatistic extends Model
{
    use HasFactory;
    protected $fillable = ['number_of_items', 'total_items_size', 'hit_rate', 'miss_rate', 'policy'];
    protected static function booted()
    {

      
    }
}
