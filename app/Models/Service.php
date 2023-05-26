<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;



class Service extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'created_by',
        'name',
        'comment'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }


    public function scopeSearch(Builder $query, Request $request): Builder
    {
        $name = $request->input('name');


        if ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        return $query;
    }

    public function getActivitylogOptions() : LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
