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
use Spatie\Tags\HasTags;


/**
 * App\Models\Invoice
 *
 * @property int $id
 * @property int $created_by
 * @property int $partner_id
 * @property string $payment_mode
 * @property string $issue_date
 * @property string $fulfillment_date
 * @property string $expiration_date
 * @property string $order_number
 * @property float $net
 * @property float $tax
 * @property float $gross
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Admin $createdBy
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InvoiceItem[] $invoiceItems
 * @property-read int|null $invoice_items_count
 * @property-read \App\Models\Partner $partner
 * @method static Builder|Invoice newModelQuery()
 * @method static Builder|Invoice newQuery()
 * @method static \Illuminate\Database\Query\Builder|Invoice onlyTrashed()
 * @method static Builder|Invoice query()
 * @method static Builder|Invoice search(\Illuminate\Http\Request $request)
 * @method static Builder|Invoice whereComment($value)
 * @method static Builder|Invoice whereCreatedAt($value)
 * @method static Builder|Invoice whereCreatedBy($value)
 * @method static Builder|Invoice whereDeletedAt($value)
 * @method static Builder|Invoice whereExpirationDate($value)
 * @method static Builder|Invoice whereFulfillmentDate($value)
 * @method static Builder|Invoice whereGross($value)
 * @method static Builder|Invoice whereId($value)
 * @method static Builder|Invoice whereIssueDate($value)
 * @method static Builder|Invoice whereNet($value)
 * @method static Builder|Invoice whereOrderNumber($value)
 * @method static Builder|Invoice wherePartnerId($value)
 * @method static Builder|Invoice wherePaymentMode($value)
 * @method static Builder|Invoice whereTax($value)
 * @method static Builder|Invoice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Invoice withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Invoice withoutTrashed()
 * @mixin \Eloquent
 */
class Invoice extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasTags;

    const PAYMENT_MODES = [
        'transfer' => "Transfer",
        'cash' => "Cash"
    ];

    protected $fillable = [
        'created_by',
        'partner_id',
        'order_number',
        'payment_mode',
        'issue_date',
        'fulfillment_date',
        'expiration_date',
        'net',
        'tax',
        'gross',
        'comment'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function scopeSearch(Builder $query, Request $request): Builder
    {
        $partner = $request->input('partner');
        $orderNumber = $request->input('order_number');
        $fulfillmentFromDate = $request->input('fulfillment_from_date');
        $fulfillmentToDate = $request->input('fulfillment_to_date');
        $expirationFromDate = $request->input('expiration_from_date');
        $expirationToDate = $request->input('expiration_to_date');
        $fromGross = $request->input('from_gross');
        $toGross = $request->input('to_gross');

        if ($partner) {
            $query->whereHas('partner', function (Builder $query) use ($partner) {
                $query->where('name', 'LIKE', '%' . $partner . '%');
            });
        }
        if ($orderNumber) {
            $query->where('order_number', 'LIKE', '%' . $orderNumber . '%');
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
