<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'incoming_kind',
        'is_confidential',
        'from_party_id',
        'requester_name',
        'book_no',
        'book_date',
        'subject',
        'keywords',
        'incoming_no',
        'incoming_date',
        'out_going_status',
    ];

    protected $casts = [
        'book_date' => 'date',
        'incoming_date' => 'date',
        'is_confidential' => 'boolean',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function fromParty()
    {
        return $this->belongsTo(Party::class, 'from_party_id');
    }

    public function referrals()
    {
        return $this->hasMany(LetterReferral::class, 'letter_id');
    }
}
