<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigrationModel extends Model
{
    use HasFactory;
    protected $fillable = ['capacity','replacment_policy'];
    protected $table = 'cache_configration';
}
