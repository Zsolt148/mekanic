<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Role;
use App\Notifications\AdminInvitedNotification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\AdminTestCase;

class AdminCrudTest extends AdminTestCase
{
    use WithFaker;

    public function test_admins_page_cannot_be_rendered_as_not_superadmin()
    {
        $user = Admin::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('admins.index'));
        $response->assertStatus(403);

        $response = $this->get(route('admins.create'));
        $response->assertStatus(403);
    }

    public function test_admins_page_can_be_rendered_as_superadmin()
    {
        $user = Admin::factory()->create();
        $user->assignRole('superadmin');

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);

        $response = $this->get(route('admins.index'));
        $response->assertStatus(200);

        $response = $this->get(route('admins.create'));
        $response->assertStatus(200);
    }

    public function test_invited_admin_can_register()
    {
        Notification::fake();

        $admin = Admin::factory()->create();
        $admin->assignRole('superadmin');

        $this->actingAs($admin);

        $email = $this->faker->email;

        $response = $this->post(route('admins.store'), [
            'name' => $this->faker->name,
            'email' => $email,
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('password_resets', [
            'email' => $email
        ]);

        $this->app['auth']->guard($this->guard)->logout();

        $user = Admin::whereEmail($email)->firstOrFail();

        Notification::assertSentTo(Admin::whereEmail($email)->first(), function (AdminInvitedNotification $notification) use ($user) {

            $response = $this->get(route('password.reset', [
                'token' => $notification->token,
                'email' => $user->email,
            ]));

            $response->assertStatus(200);

            $response = $this->post(route('password.update'), [
                'token' => $notification->token,
                'email' => $user->email,
                'name' => $user->name,
                'password' => 'Password123?',
                'password_confirmation' => 'Password123?'
            ]);

            $response->assertRedirect(route('login'));

            $response = $this->post('login', [
                'email' => $user->email,
                'password' => 'Password123?'
            ]);

            $response->assertSessionHasNoErrors();

            //$this->assertAuthenticated($this->guard);

            return true;
        });
    }

    public function test_invited_admin_cannot_register_with_expired_token()
    {
        Notification::fake();

        $admin = Admin::factory()->create();
        $admin->assignRole('superadmin');

        $this->actingAs($admin);

        $response = $this->post(route('admins.store'), [
            'name' => $this->faker->name,
            'email' => $email = $this->faker->email,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('password_resets', [
            'email' => $email
        ]);

        $this->app['auth']->guard()->logout();

        Notification::assertSentTo(Admin::whereEmail($email)->first(), function (AdminInvitedNotification $notification) use ($email) {

            $response = $this->get(route('password.reset', [
                'token' => $notification->token,
                'email' => $email,
            ]));

            $response->assertStatus(200);

            $this->travel(config('auth.passwords.admins.expire') + 1)->minutes();

            $response = $this->post(route('password.update'), [
                'token' => $notification->token,
                'email' => $email,
                'password' => 'Password123?',
                'password_confirmation' => 'Password123?'
            ]);

            $response->assertSessionHasErrors();
            $this->assertGuest($this->guard);

            return true;
        });
    }

    public function test_admin_can_be_shown_as_superadmin()
    {
        $admin = Admin::factory()->create();
        $admin->assignRole('superadmin');

        $this->actingAs($admin);

        $user = Admin::query()->whereNot('id', $admin->id)->first();

        $response = $this->get(route('admins.show', $user));
        $response->assertStatus(200);
    }

    public function test_admin_can_be_updated_as_superadmin()
    {
        $admin = Admin::factory()->create();
        $admin->assignRole('superadmin');

        $this->actingAs($admin);

        $user = Admin::query()->whereNot('id', $admin->id)->first();

        $response = $this->get(route('admins.edit', $user));
        $response->assertStatus(200);

        $response = $this->put(route('admins.update', $user), [
            'name' => 'New Name',
            'email' => 'new@email.com',
            'status' => Admin::STATUS_REGISTERED,
            'roles' => Role::all()->pluck('id')->toArray()
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('admins', [
            'name' => 'New Name',
            'email' => 'new@email.com',
            'status' => Admin::STATUS_REGISTERED,
        ]);

        $this->assertNotEmpty($user->roles);
        $this->assertTrue($user->hasRole('superadmin'));
    }

    public function test_admin_can_be_deleted_as_superadmin()
    {
        $admin = Admin::factory()->create();
        $admin->assignRole('superadmin');

        $this->actingAs($admin);

        $user = Admin::query()->whereNot('id', $admin->id)->first();

        $response = $this->delete(route('admins.destroy', $user));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('admins', [
            'email' => $user->email,
            'deleted_at' => now(),
        ]);
    }

    public function test_admin_can_be_restored_as_superadmin()
    {
        $admin = Admin::factory()->create();
        $admin->assignRole('superadmin');

        $this->actingAs($admin);

        $user = Admin::factory()->create([
            'deleted_at' => now()
        ]);

        $response = $this->patch(route('admins.restore', $user));

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('admins', [
            'email' => $user->email,
            'deleted_at' => null,
        ]);
    }
}
