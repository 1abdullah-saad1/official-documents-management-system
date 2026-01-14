<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'name',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function lettersFrom()
    {
        return $this->hasMany(Letter::class, 'from_party_id');
    }

    public function letterReferralsTo()
    {
        return $this->hasMany(LetterReferral::class, 'to_party_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
