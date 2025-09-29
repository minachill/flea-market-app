<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

        /** @test */
    public function test_it_requires_email_to_login()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /** @test */
    public function test_it_requires_password_to_login()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }
    /** @test */
    // メールアドレス、パスワード未入力 → エラーメッセージ
    public function test_it_requires_email_and_password()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
            'password' => 'パスワードを入力してください',
        ]);
    }

    /** @test */
    	// 入力情報が間違っている → エラーメッセージ
    public function test_it_rejects_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    /** @test */
    // 正しい情報ならログイン成功（商品一覧へリダイレクト）※未認証ユーザーなら /email/verify へリダイレクト
    public function test_it_redirects_unverified_user_to_verification_notice()
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
    // 初回ログイン → プロフィール編集画面
    public function test_it_redirects_first_time_login_to_profile_edit()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_profile_set' => false,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/mypage/edit');
    }

    /** @test */
    // 認証済み・プロフィール設定済み → 商品一覧
    public function test_it_redirects_verified_user_with_profile_to_items_index()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'is_profile_set' => true,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
    }
}