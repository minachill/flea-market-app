<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    // ログイン済みのユーザーはコメントを送信できる → DB保存され、コメント数が増加する
    public function test_it_allows_authenticated_user_to_post_comment_and_increases_count()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->assertEquals(0, Comment::count());

        $this->actingAs($user)->post("/item/{$item->id}/comment", [
            'comment' => 'テストコメントです',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメントです',
        ]);

        $this->assertEquals(1, Comment::count());
    }

    /** @test */
    // 未ログインのユーザーはコメントを送信できない
    public function test_it_does_not_allow_guest_to_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comment", [
            'comment' => 'ゲストのコメント',
        ]);

        $response->assertRedirect('/login'); // ログインページに飛ばされる
        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストのコメント',
        ]);
    }

    /** @test */
    // コメントが入力されていない場合、バリデーションメッセージが表示される
    public function test_it_shows_error_when_comment_is_empty()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']); // バリデーションエラー
    }

    /** @test */
    // コメントが255字以上の場合、バリデーションメッセージが表示される
    public function test_it_shows_error_when_comment_is_too_long()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)->post("/item/{$item->id}/comment", [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors(['comment']); // バリデーションエラー
    }
}