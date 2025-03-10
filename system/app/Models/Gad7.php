<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gad7 extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'user_id', 'anxious_nervous', 'uncontrollable_worrying', 'worrying_too_much', 'trouble_relaxing', 'restless', 'irritable', 'afraid'];
    
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
