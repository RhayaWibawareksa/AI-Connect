<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostInteractionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_vote_and_bookmark_a_post(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);
        $post = Post::create([
            'title' => 'Contoh postingan',
            'content' => 'Isi postingan',
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'published',
            'votes' => 0,
        ]);

        $voteResponse = $this->actingAs($user)->postJson('/posts/' . $post->id . '/vote', [
            'type' => 'up',
        ]);

        $voteResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('votes', 1);

        $bookmarkResponse = $this->actingAs($user)->postJson('/posts/' . $post->id . '/bookmark');

        $bookmarkResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('bookmarked', true);
    }

    public function test_commenting_on_another_users_post_creates_notification(): void
    {
        $postOwner = User::factory()->create(['name' => 'Pemilik Post']);
        $commenter = User::factory()->create(['name' => 'Komentator']);

        $category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        $post = Post::create([
            'title' => 'Postingan target',
            'content' => 'Isi contoh',
            'user_id' => $postOwner->id,
            'category_id' => $category->id,
            'status' => 'published',
            'votes' => 0,
        ]);

        $response = $this->actingAs($commenter)->post('/posts/' . $post->id . '/comments', [
            'content' => 'Komentar baru',
        ]);

        $response->assertRedirect(route('posts.show', $post->id));
        $this->assertDatabaseHas('notifications', [
            'user_id' => $postOwner->id,
            'type' => 'comment',
        ]);

        $notification = Notification::where('user_id', $postOwner->id)->first();
        $this->assertNotNull($notification);
        $this->assertEquals('comment', $notification->type);
        $this->assertEquals('Komentator', $notification->data['commenter_name']);
        $this->assertEquals($post->title, $notification->data['post_title']);
    }
}
