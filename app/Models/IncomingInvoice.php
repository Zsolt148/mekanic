<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

/**
 * App\Models\IncomingInvoice
 *
 * @property int $id
 * @property int $created_by
 * @property string $id_number
 * @property string $subject
 * @property float $net
 * @property float $gross
 * @property string $payment_mode
 * @property string $fulfillment_date
 * @property string $expiration_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Admin $createdBy
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @method static Builder|IncomingInvoice newModelQuery()
 * @method static Builder|IncomingInvoice newQuery()
 * @method static \Illuminate\Database\Query\Builder|IncomingInvoice onlyTrashed()
 * @method static Builder|IncomingInvoice query()
 * @method static Builder|IncomingInvoice search(?string $locale = null)
 * @method static Builder|IncomingInvoice whereCreatedAt($value)
 * @method static Builder|IncomingInvoice whereCreatedBy($value)
 * @method static Builder|IncomingInvoice whereDeletedAt($value)
 * @method static Builder|IncomingInvoice whereExpirationDate($value)
 * @method static Builder|IncomingInvoice whereFulfillmentDate($value)
 * @method static Builder|IncomingInvoice whereGross($value)
 * @method static Builder|IncomingInvoice whereId($value)
 * @method static Builder|IncomingInvoice whereIdNumber($value)
 * @method static Builder|IncomingInvoice whereNet($value)
 * @method static Builder|IncomingInvoice wherePaymentMode($value)
 * @method static Builder|IncomingInvoice whereSubject($value)
 * @method static Builder|IncomingInvoice whereUpdatedAt($value)
 * @method static Builder|IncomingInvoice withAllTags(\ArrayAccess|\App\Models\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|IncomingInvoice withAllTagsOfAnyType($tags)
 * @method static Builder|IncomingInvoice withAnyTags(\ArrayAccess|\App\Models\Tag|array|string $tags, ?string $type = null)
 * @method static Builder|IncomingInvoice withAnyTagsOfAnyType($tags)
 * @method static \Illuminate\Database\Query\Builder|IncomingInvoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|IncomingInvoice withoutTrashed()
 * @mixin \Eloquent
 */
class IncomingInvoice extends Model
{
    use HasTags, HasFactory, SoftDeletes, LogsActivity;

    const PAYMENT_MODES = [
        'transfer' => "Transfer",
        'cash' => "Cash",
        'bank_card' => "Bank card payment"
    ];

    protected $fillable = [
        'created_by',
        'id_number',
        'subject',
        'net',
        'gross',
        'payment_mode',
        'fulfillment_date',
        'expiration_date'
    ];

    protected $with = [
        'tags'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function scopeSearch(Builder $query, Request $request): Builder
    {
        $idNumber = $request->input('id_number');
        $issuer = $request->input('issuer');
        $fulfillmentFromDate = $request->input('fulfillment_from_date');
        $fulfillmentToDate = $request->input('fulfillment_to_date');
        $expirationFromDate = $request->input('expiration_from_date');
        $expirationToDate = $request->input('expiration_to_date');
        $fromGross = $request->input('from_gross');
        $toGross = $request->input('to_gross');

        if ($idNumber) {
            $query->where('id_number', 'LIKE', '%' . $idNumber . '%');
        }
        if ($issuer) {
            $query->whereHas('tags', function ($q) use ($issuer) {
                $q
					->where('name', 'LIKE', '%' . $issuer . '%')
                	->where('type', 'issuer');
            });
        }
        if ($fulfillmentFromDate && $fulfillmentToDate) {
            $query->whereBetween('fulfillment_date', [$fulfillmentFromDate, $fulfillmentToDate]);
        }else if($fulfillmentFromDate){
            $query->whereDate('fulfillment_date', $fulfillmentFromDate);
        }else if($fulfillmentToDate){
            $query->whereDate('fulfillment_date', $fulfillmentToDate);
        }
        if ($expirationFromDate && $expirationToDate) {
            $query->whereBetween('expiration_date', [$expirationFromDate, $expirationToDate]);
        }else if($expirationFromDate){
            $query->whereDate('expiration_date', $expirationFromDate);
        }else if($expirationToDate){
            $query->whereDate('expiration_date', $expirationToDate);
        }
        if ($fromGross) {
            $query->where('gross', '>=', $fromGross);
        }
        if ($toGross) {
            $query->where('gross', '<=', $toGross);
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
