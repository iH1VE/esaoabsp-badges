<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_id',
        'code',
        'title',
        'description',
        'image_path',
        'hours',
        'skills',
        'criteria',
        'is_active',
    ];

    protected $casts = [
        'skills' => 'array',
        'criteria' => 'array',
        'is_active' => 'boolean',
        'hours' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $badge) {
            if (empty($badge->public_id)) {
                $badge->public_id = (string) Str::uuid();
            }
        });
    }

    public function issuances()
    {
        return $this->hasMany(Issuance::class);
    }

    public function pathwaysAwarding()
    {
        return $this->hasMany(Pathway::class, 'award_badge_id');
    }

    public function pathwayRequirements()
    {
        return $this->hasMany(PathwayRequirement::class, 'required_badge_id');
    }
}
