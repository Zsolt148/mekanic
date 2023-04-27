<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Activity as BaseActivity;

/**
 * Spatie\Activitylog\Models\Activity.
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $causer_type
 * @property int|null $causer_id
 * @property \Illuminate\Support\Collection|null $properties
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $causer
 * @property-read \Illuminate\Support\Collection $changes
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity forBatch(string $batchUuid)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity forEvent(string $event)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity hasBatch()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity inLog($logNames)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity query()
 * @property string|null $event
 * @property string|null $batch_uuid
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereBatchUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Activity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Activity extends BaseActivity
{
    const SEARCHABLE = [
		Admin::class
	];

    public function subject(): MorphTo
    {
        $relation = $this->morphTo()->withTrashed();

//        if (config('activitylog.subject_returns_soft_deleted_models') && in_array(SoftDeletes::class, class_uses($relation), true)) {
//            return $relation->withTrashed();
//        }

        return $relation;
    }
}
