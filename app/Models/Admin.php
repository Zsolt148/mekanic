<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use App\Traits\HasProfilePhoto;
use App\Traits\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,
        Notifiable,
        HasProfilePhoto,
        HasRoles,
        HasPermissions,
        TwoFactorAuthenticatable,
        LogsActivity,
        SoftDeletes,
        Searchable;

    protected $table = "admins";

    const SEARCHABLE_FIELDS = ['id', 'name', 'email'];

	const LOGIN_ATTEMPTS = 5;

    const STATUS_INVITED = 'invited';
    const STATUS_REGISTERED = 'registered';
    const STATUS_BLOCKED= 'blocked';

    const STATUSES = [
        self::STATUS_INVITED => 'Invited',
        self::STATUS_REGISTERED => 'Registered',
        self::STATUS_BLOCKED => 'Blocked',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'password',
        'locale',
        'last_login_at',
        'blocked_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'blocked_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'two_factor_enabled',
    ];

    protected $with = [
        'roles',
        'permissions',
    ];

    public function toSearchableArray()
    {
        return $this->only(self::SEARCHABLE_FIELDS);
    }

    public function getActivitylogOptions() : LogOptions
    {
        return LogOptions::defaults()
            ->dontLogIfAttributesChangedOnly(['updated_at', 'last_login_at', 'remember_token'])
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function isInvited(): bool
    {
        return $this->status === self::STATUS_INVITED;
    }

    public function isRegistered(): bool
    {
        return $this->status === self::STATUS_REGISTERED;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

	public function scopeActive(Builder $query)
	{
		return $query->where('status', self::STATUS_REGISTERED);
	}

    public function getStatusText()
    {
        switch(true) {
            case $this->isRegistered():
                return __('Registered');
            case $this->isInvited():
                return __('Invited');
            case $this->isBlocked():
                return __('Blocked');
        }
    }

    public function getStatusDate()
    {
        switch(true) {
            case $this->isRegistered():
            case $this->isInvited():
                return $this->created_at;
            case $this->isBlocked():
                return $this->blocked_at;
        }

        return null;
    }

    /**
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * @param $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
