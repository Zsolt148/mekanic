<?php

namespace App\Http\Middleware;

use App\Helpers\AdminFeatures;
use App\Helpers\Features;
use App\Http\Resources\SettingsResource;
use App\Models\Admin;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $admin = $request->user('admin');

        return array_merge(parent::share($request), [
            'env' => app()->environment(),

            'csrf_token' => csrf_token(),

            'url' => config('app.url'),

            'locale' => fn () => app()->getLocale(),

			'locales' => mapForSelect(config('app.locales'), sameValue: false),

			'language' => fn () => translations(
				base_path('lang/hu.json')
			),

            'auth' => [
                'user' => $admin,
                'roles' => optional($admin, fn (Admin $admin) =>
                    $admin->roles->pluck('name')->toArray()
                ),
				'roles_text' => optional($admin, fn (Admin $admin) =>
					$admin->roles->pluck('name')->map(fn ($name) => __('role.'.$name))->join(', ')
				),
                'permissions' => optional($admin, fn (Admin $admin) =>
					// All permissions which apply on the user (inherited and direct)
                    $admin->getAllPermissions()->pluck('name')->toArray()
                ),
                'role' => [
                    'isSuperadmin' => Gate::allows('isSuperadmin', $admin),
                ]
            ],

            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },

            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],

            'hasAdminProxy' => $request->session()->has('admin-proxy-original'),

            'features' => [
                'canRegister' => AdminFeatures::canRegister(),
                'hasProfileFeatures' => AdminFeatures::hasProfileFeatures(),
                'hasSecurityFeatures' => AdminFeatures::hasSecurityFeatures(),
				'hasLogs' => AdminFeatures::hasLogs(),
            ]
        ]);
    }
}
