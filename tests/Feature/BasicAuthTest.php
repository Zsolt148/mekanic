<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Settings;
use Tests\AdminTestCase;

class BasicAuthTest extends AdminTestCase
{
    public function test_basic_auth_middleware_works()
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        app(Settings::class)->update([
            'htaccess_username' => 'username',
            'htaccess_password' => 'password',
        ]);

        $response = $this->get('/');

        $response->assertStatus(401);
    }

    public function test_basic_auth_middleware_bypass()
    {
        app(Settings::class)->update([
            'htaccess_username' => 'username',
            'htaccess_password' => 'password',
        ]);

        $response = $this->get('/', [
            'PHP_AUTH_USER' => 'username', 'PHP_AUTH_PW' => 'password'
        ]);

        $response->assertStatus(200);
    }

    public function test_basic_auth_username_and_password_required()
    {
        $this->actingAs($user = Admin::factory()->create());
        $user->assignRole('superadmin');

        $response = $this->post('/settings/security', [
            'username' => 'username',
            'password' => ''
        ]);

        $response->assertSessionHasErrors();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_basic_auth_username_and_password_update()
    {
        $this->actingAs($user = Admin::factory()->create());
        $user->assignRole('superadmin');

        $response = $this->post('/settings/security', [
            'username' => 'username',
            'password' => 'password'
        ]);

        $response->assertSessionHasNoErrors();

        $settings = app(Settings::class)->fresh();
        $this->assertEquals($settings->htaccess_username, 'username');
        $this->assertEquals($settings->htaccess_password, 'password');

        $response = $this->get('/');

        $response->assertStatus(401);

        $response = $this->get('/', [
            'PHP_AUTH_USER' => 'username', 'PHP_AUTH_PW' => 'password'
        ]);

        $response->assertStatus(200);
    }
}
