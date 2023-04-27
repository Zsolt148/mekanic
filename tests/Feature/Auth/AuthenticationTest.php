<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Tests\AdminTestCase;

class AuthenticationTest extends AdminTestCase
{
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = Admin::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated($this->guard);
        $response->assertRedirect(RouteServiceProvider::ADMIN);
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = Admin::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest($this->guard);
        $response->assertSessionHasErrors();
    }

    public function test_users_can_not_authenticate_when_blocked()
    {
        $user = Admin::factory()->create([
            'status' => Admin::STATUS_BLOCKED,
            'blocked_at' => now()
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest($this->guard);
        $response->assertSessionHasErrors();
    }
}
