<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PathwayRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'pathway_id',
        'required_badge_id',
        'min_count',
    ];

    protected $casts = [
        'min_count' => 'integer',
    ];

    public function pathway()
    {
        return $this->belongsTo(Pathway::class);
    }

    public function requiredBadge()
    {
        return $this->belongsTo(Badge::class, 'required_badge_id');
    }
}
