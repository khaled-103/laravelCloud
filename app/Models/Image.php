<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['key','image'];

    // $list = new SplQueue();
        // $list->push("khaled"); //add top
        // $list->push('Ahmed');
        // // $list->pop(); //remove end
        // $list->shift(); //remove start
        // $list->unshift("yzeed");
        // dd($list);

}
