<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\StatusResource;
use App\Models\Admin;
use App\Notifications\AdminInvitedNotification;
use App\Traits\AdminForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    use AdminForm;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('viewAny', Admin::class);

        $users = Admin::query()
            ->with('roles', 'permissions')
            ->get();

        $deletedUsers = Admin::query()
            ->with('roles', 'permissions')
            ->onlyTrashed()
            ->get();

        return Inertia::render('Admins/Index', [
            'admins' => AdminResource::collection($users),
            'deletedAdmins' => AdminResource::collection($deletedUsers),
            'statuses' => StatusResource::make(Admin::STATUSES),
            'roles' => RoleResource::collection(Role::all()),
            'permissions' => PermissionResource::collection(Permission::all()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', Admin::class);

		$supervisors = Admin::query()
			->withoutEagerLoads() // no need for roles and permissions
			->get();

        return Inertia::render('Admins/Create', [
            'admins' => AdminResource::collection($supervisors),
            'permissions' => PermissionResource::collection(Permission::all()),
            'roles' => RoleResource::collection(Role::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Admin::class);

        $validated = $request->validate($this->adminFormRules(null, true));

        /** @var Admin $admin */
        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => Admin::STATUS_INVITED,
        ]);

        $admin->syncRoles($request->roles);
        $admin->syncPermissions($request->permissions);

        $admin->notify(new AdminInvitedNotification(Password::broker('admins')->createToken($admin)));

        return redirect()->route('admins.index')->with('success', __('Successfully created'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show($adminId)
    {
		/** @var Admin $admin */
		$admin = Admin::query()
			->withTrashed()
			->findOrFail($adminId);

		Gate::authorize('view', $admin);

		return Inertia::render('Admins/Show', [
            'user' => AdminResource::make($admin),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit($adminId)
    {
        /** @var Admin $admin */
        $admin = Admin::query()
			->withTrashed()
			->findOrFail($adminId);

        Gate::authorize('update', $admin);

        return Inertia::render('Admins/Edit', [
            'user' => AdminResource::make($admin),
            'roles' => RoleResource::collection(Role::all()),
            'statuses' => StatusResource::make(Admin::STATUSES),
            'permissions' => PermissionResource::collection(Permission::all()),
            'admins' => AdminResource::collection(Admin::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        /** @var Admin $admin */
        $admin = Admin::withTrashed()->findOrFail($userId);

        Gate::authorize('update', $admin);

        $validated = $request->validate($this->adminFormRules($admin, true));

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
            'blocked_at' => $validated['status'] == Admin::STATUS_BLOCKED ? now() : null,
        ]);

        $admin->syncRoles($validated['roles']);
        $admin->syncPermissions($validated['permissions']);

        return redirect()->route('admins.index')->with('success', __('Successfully updated'));
    }

    /**
     * @param Admin $admin
     * @return RedirectResponse
     */
    public function destroy(Admin $admin)
    {
        Gate::authorize('delete', $admin);

        $admin->delete();

        return redirect()->route('admins.index')->with('success', __('Successfully deleted'));
    }

    /**
     * @param Request $request
     * @param Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function reinvite(Request $request, Admin $admin)
    {
        Gate::authorize('update', $admin);

        $admin->notify(new AdminInvitedNotification(Password::broker('admins')->createToken($admin)));

        return back()->with('success', __('Successfully invited'));
    }

    /**
     * @param $userId
     * @return RedirectResponse
     */
    public function restore($userId)
    {
        /** @var Admin $admin */
        $admin = Admin::withTrashed()->findOrFail($userId);

        Gate::authorize('restore', $admin);

        $admin->restore();

        return redirect()->route('admins.index')->with('success', __('Successfully restored'));
    }

    /**
     * @param $userId
     * @return RedirectResponse
     */
    public function unblock($adminId)
    {
        /** @var Admin $admin */
        $admin = Admin::withTrashed()->findOrFail($adminId);

        Gate::authorize('update', $admin);

        $admin->update([
            'status' => Admin::STATUS_REGISTERED,
            'blocked_at' => null
        ]);

        return redirect()->route('admins.index')->with('success', __('Successfully unblocked'));
    }

    /**
     * @param Admin $admin
     * @return RedirectResponse
     */
    public function forceDelete($adminId)
    {
        /** @var Admin $admin */
        $admin = Admin::withTrashed()->findOrFail($adminId);

        Gate::authorize('forceDelete', $admin);

        $admin->forceDelete();

        return redirect()->route('admins.index')->with('success', __('Successfully force deleted'));
    }

    /**
     * @param Admin $admin
     * @return RedirectResponse
     */
    public function deletePhoto(Admin $admin)
    {
        Gate::authorize('update', $admin);

        $admin->deleteProfilePhoto();

        return back()->with('success', __('Successfully deleted the photo'));
    }
}
