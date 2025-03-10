<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptsd5 extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'user_id', 'nightmares', 'hard_not_to_think', 'on_guard', 'felt_numb', 'felt_guilty'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
