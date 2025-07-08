<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'theater_id', 'date', 'start_time'];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class)->withTrashed();
    }

    public function theater(): BelongsTo
    {
        return $this->belongsTo(Theater::class)->withTrashed();
    }

    public function isSoldOut(): bool
    {
        $totalSeats = $this->theater->seats->count();
        $soldTickets = $this->tickets->count();

        return $totalSeats <= $soldTickets;
    }
}
