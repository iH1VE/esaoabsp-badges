<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Issuance extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge_id',
        'public_id',
        'recipient_name',
        'recipient_email',
        'evidence',
        'issued_at',
        'status',
        'revoked_at',
        'revocation_reason',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
        'evidence' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Issuance $i) {
            if (empty($i->public_id)) {
                $i->public_id = (string) Str::uuid();
            }
        });
    }

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    // Acessores para o Blade (pra você poder usar $issuance->courses etc)
    public function getTotalHoursAttribute()
    {
        return $this->evidence['total_hours'] ?? null;
    }

    public function getCoursesAttribute()
    {
        return $this->evidence['courses'] ?? null;
    }

    public function getSkillsAttribute()
    {
        return $this->evidence['skills'] ?? null;
    }

    public function getCriteriaAttribute()
    {
        return $this->evidence['criteria'] ?? null;
    }

    public function getIsRevokedAttribute(): bool
    {
        return !empty($this->revoked_at);
    }
}
