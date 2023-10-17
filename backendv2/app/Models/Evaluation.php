<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'softskills',
        'hardskills',
        'performance',
        'autoevaluation',
        'promedio'
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
    ];

    public function user(){
      return $this->belongsTo(User::class);
    }

}