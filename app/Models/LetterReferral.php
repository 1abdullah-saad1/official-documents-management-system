<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterReferral extends Model
{
    use HasFactory;

    protected $table = 'letter_referral';

    protected $fillable = [
        'seq',
        'referral_id',
        'letter_id',
        'to_party_id',
        'through',
        'note',
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function toParty()
    {
        return $this->belongsTo(Party::class, 'to_party_id');
    }

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }
}
