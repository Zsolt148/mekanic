<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

class Car extends Model
{
    use HasTags, HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'partner_id',
        'brand',
        'type',
        'license_plate',
        'mileage',
        'chassis_number',
        'motor_number',
        'vintage',
        'ti_validity'
    ];
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function scopeSearch(Builder $query, Request $request): Builder
    {
        $partner = $request->input('partner');
        $brand = $request->input('brand');
        $type = $request->input('type');
        $licensePlate = $request->input('license_plate');
        $mileage = $request->input('mileage');
        $chassisNumber = $request->input('chassis_number');
        $motorNumber = $request->input('motor_number');
        $vintage = $request->input('vintage');
        $tiValidity = $request->input('ti_validity');

        if ($partner) {
            $query->whereHas('partner', function (Builder $query) use ($partner) {
                $query->where('name', 'LIKE', '%' . $partner . '%');
            });
        }
        if ($licensePlate) {
            $query->where('license_plate', 'LIKE', '%' . $licensePlate . '%');
        }
        if ($brand) {
            $query->where('brand', 'LIKE', '%' . $brand . '%');
        }
        if ($type) {
            $query->where('type', 'LIKE', '%' . $type . '%');
        }
        if ($mileage) {
            $query->where('mileage', 'LIKE', '%' . $mileage . '%');
        }
        if ($chassisNumber) {
            $query->where('chassis_number', 'LIKE', '%' . $chassisNumber . '%');
        }
        if ($motorNumber) {
            $query->where('motor_number', 'LIKE', '%' . $motorNumber . '%');
        }
        if ($vintage) {
            $query->where('vintage', 'LIKE', '%' . $vintage . '%');
        }
        if ($tiValidity) {
            $query->where('ti_validity', 'LIKE', '%' . $tiValidity . '%');
        }

        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
