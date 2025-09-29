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

    /** @test */
    // 会員登録したら、そのユーザーに 新規登録時の認証メールが送信される ことを確認
    public function test_it_sends_verification_email_after_registration()
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 認証メールが送信されたことを確認
        $user = User::where('email', 'test@example.com')->first();
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    // 未認証ユーザーがログインすると、 メール認証誘導画面（/email/verify）にリダイレクトされる ことを確認
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

    /** @test */
    // 未認証ユーザーが /email/verify を開いたときに、 メール認証誘導画面が正常に表示される ことを確認。
// （＝ページが200で返る＆「認証はこちらから」ボタンが見える）
    public function test_it_allows_user_to_access_verification_page_from_notice()
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/email/verify')
            ->assertStatus(200) // 誘導画面が表示される
            ->assertSee('認証はこちらから'); // ボタン文言を確認
    }

    /** @test */
    // メール内の認証リンクをクリックしたときに、ユーザーが認証済みになる、認証後は /mypage/edit にリダイレクトされることを確認。
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
            ->assertRedirect('/mypage/edit'); // 認証後はプロフィール編集画面

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        Event::assertDispatched(Verified::class);
    }

/** @test */
    // 「認証メール再送」ボタンを押したときに、新しい認証メールが送られる。セッションに verification-link-sent が入ることを確認。
    public function test_it_resends_verification_email()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post('/email/verification-notification')
            ->assertStatus(302) // 再送後はリダイレクトが返る（仕様に合わせて200でもOK）
            ->assertSessionHas('status', 'verification-link-sent');

        // ✅ 本当に対象ユーザーに VerifyEmail 通知が送られたかを確認
        Notification::assertSentTo(
            [$user],VerifyEmail::class
        );
}
}