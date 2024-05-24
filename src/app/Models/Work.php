<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    //userモデルと1対多のリレーション
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    //breakingモデルと1対多のリレーション
    public function breakings(){
        return $this->hasMany('App\Models\Breaking');
    }
}