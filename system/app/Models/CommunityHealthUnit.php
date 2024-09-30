<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityHealthUnit extends Model
{
    use HasFactory;

    protected $fillable = ["name", "subcounty_id"];

    public function subcounty()
    {
        return $this->belongsTo(Subcounty::class, "subcounty_id");
    }
}
