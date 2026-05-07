<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Pathway extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'title',
        'description',
        'award_badge_id',
        'auto_award',
        'is_active',
    ];

    protected $casts = [
        'auto_award' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Pathway $pathway) {
            if (empty($pathway->public_id)) {
                $pathway->public_id = (string) Str::uuid();
            }
        });
    }

    public function awardBadge()
    {
        return $this->belongsTo(Badge::class, 'award_badge_id');
    }

    public function requirements()
    {
        return $this->hasMany(PathwayRequirement::class);
    }
}
