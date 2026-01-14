<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'image_path',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function letterRelations()
    {
        return $this->hasMany(LetterReferral::class, 'referral_id');
    }
}
