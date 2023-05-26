<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Worksheet extends Model
{
    use HasFactory;

    const TYPE_OFFER = 'offer';
    const TYPE_WORKSHEET = 'worksheet';

    const TYPES = [
        self::TYPE_OFFER => 'Ajánlat',
        self::TYPE_WORKSHEET => 'Munkalap'
    ];

    protected $fillable = [
        'type',
        'admin_id',
        'partner_id',
        'car_id',
        'done_at'
    ];

    protected $casts = [
        'done_at' => 'datetime'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'worksheet_service')
            ->withTimestamps();
    }

    public function isDone(): bool
    {
        return $this->done_at !== null;
    }
}
