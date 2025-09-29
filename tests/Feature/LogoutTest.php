<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // 「ログアウト後に正しくトップへ戻される」＋「セッションが切れている」
    public function test_it_logs_out_and_redirects_to_home()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'is_profile_set' => true,
        ]);

        // ログイン状態にする
        $this->actingAs($user);

        // ログアウト実行
        $response = $this->post('/logout');

        // トップページにリダイレクトされる
        $response->assertRedirect('/');

        // ログアウト後にマイページに行こうとするとログイン画面にリダイレクトされる
        $this->get('/mypage')->assertRedirect('/login');
    }
}