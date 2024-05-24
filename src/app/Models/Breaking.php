<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breaking extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'breaking_start_time' => 'datetime',
        'breaking_end_time' => 'datetime',
    ];

    //workモデルと1対多のリレーション
    public function work(){
        return $this->belongsTo('App\Models\Work');
    }
}
