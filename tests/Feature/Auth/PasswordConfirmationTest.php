<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use Tests\AdminTestCase;

class PasswordConfirmationTest extends AdminTestCase
{
    public function test_password_can_be_confirmed()
    {
        $user = Admin::factory()->create();

        $response = $this->actingAs($user)->post('/profile/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertJson(['password' => 'password']);
        $response->assertSessionHasNoErrors();
    }

    public function test_password_is_not_confirmed_with_invalid_password()
    {
        $user = Admin::factory()->create();

        $response = $this->actingAs($user)->post('/profile/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
