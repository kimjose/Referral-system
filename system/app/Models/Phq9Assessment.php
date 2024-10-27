<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phq9Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'assessment_date',
        'little_interest',
        'feeling_down',
        'trouble_sleeping',
        'feeling_tired',
        'poor_appetite',
        'feeling_bad_about_self',
        'trouble_concentrating',
        'slow_or_fidgety',
        'thoughts_of_self_hurt',
        'difficult_caused'
    ];

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function user(){
        $this->belongsTo(User::class);
    }
}
