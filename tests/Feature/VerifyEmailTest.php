<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_sends_verification_email_after_registration()
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);


        $user = User::where('email', 'test@example.com')->first();
        $user->forceFill(['email_verified_at' => null])->save();
            Notification::assertSentTo(
        [$user],
        VerifyEmail::class);
    }


    public function test_it_redirects_unverified_user_to_verify_notice_on_login()
    {
        $user = User::factory()->unverified()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/email/verify');
    }


    public function test_it_allows_user_to_access_verification_page_from_notice()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/email/verify')
            ->assertStatus(200)
            ->assertSee('認証はこちらから');
    }


    public function test_it_verifies_user_with_valid_url()
    {
        Event::fake();

        $user = User::factory()->unverified()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)
            ->get($url)
            ->assertRedirect('/profile/edit');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        Event::assertDispatched(Verified::class);
    }


    public function test_it_resends_verification_email()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertStatus(302)
            ->assertSessionHas('status', 'verification-link-sent');

        Notification::assertSentTo(
            [$user],VerifyEmail::class
        );
}
}