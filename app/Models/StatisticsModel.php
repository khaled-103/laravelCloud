<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticsModel extends Model
{
    use HasFactory;
    protected $fillable = ['number_of_items','total_items_size','hit_rate','miss_rate','count_requests','policy'];
    protected $table = 'cache_statistics';
}
