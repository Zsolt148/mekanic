<?php

namespace Tests\Feature\Auth;

use App\Helpers\AdminFeatures;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\AdminTestCase;

class EmailVerificationTest extends AdminTestCase
{
    public function test_email_verification_screen_can_be_rendered()
    {
        if (! AdminFeatures::hasEmailVerification()) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $user = Admin::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        if (! AdminFeatures::hasEmailVerification()) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        Event::fake();

        $user = Admin::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(RouteServiceProvider::ADMIN.'?verified=1');
    }

    public function test_email_can_not_verified_with_invalid_hash()
    {
        if (! AdminFeatures::hasEmailVerification()) {
            return $this->markTestSkipped('Email verification not enabled.');
        }

        $user = Admin::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
