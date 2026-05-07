<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trail extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_active',
        'award_badge_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'trail_badges')
            ->withPivot('order')
            ->orderBy('pivot_order');
    }

    public function awardBadge()
    {
        return $this->belongsTo(Badge::class, 'award_badge_id');
    }
}
