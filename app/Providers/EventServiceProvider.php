<?php

namespace App\Providers;

use App\Events\Blocked;
use App\Events\Failed;
use App\Events\MentionEvent;
use App\Events\TaskCreated;
use App\Listeners\BlockedListener;
use App\Listeners\FailedLoginListener;
use App\Listeners\LoginListener;
use App\Listeners\RegisteredListener;
use App\Listeners\SendEmailVerificationNotification;
use App\Listeners\SendNewTaskNotification;
use App\Listeners\VerifiedListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event to listener mappings for the application.
	 *
	 * @var array<class-string, array<int, class-string>>
	 */
	protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class,
			RegisteredListener::class,
		],
		Login::class => [
			LoginListener::class
		],
		Failed::class => [
			FailedLoginListener::class
		],
		Blocked::class => [
			BlockedListener::class
		],
		Verified::class => [
			VerifiedListener::class
		],
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{

	}

	/**
	 * Determine if events and listeners should be automatically discovered.
	 *
	 * @return bool
	 */
	public function shouldDiscoverEvents()
	{
		return false;
	}
}
