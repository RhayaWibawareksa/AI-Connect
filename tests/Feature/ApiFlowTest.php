<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_and_login_return_auth_token(): void
    {
        $registerResponse = $this->postJson('/api/register', [
            'name' => 'Tester',
            'username' => 'tester',
            'email' => 'tester@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $registerResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token']]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'tester@example.com',
            'password' => 'secret123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_user_can_create_post_comment_and_report(): void
    {
        $user = User::create([
            'name' => 'Tester',
            'username' => 'tester',
            'email' => 'tester@example.com',
            'password' => 'secret123',
            'role' => 'user',
        ]);

        $category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        $postResponse = $this->actingAs($user, 'sanctum')->postJson('/api/posts', [
            'title' => 'Judul Post',
            'content' => 'Isi konten',
            'category_id' => $category->id,
        ]);

        $postResponse->assertStatus(201)
            ->assertJsonPath('data.title', 'Judul Post');

        $post = Post::latest()->first();

        $commentResponse = $this->actingAs($user, 'sanctum')->postJson('/api/posts/'.$post->id.'/comments', [
            'content' => 'Komentar bagus',
        ]);

        $commentResponse->assertStatus(201)
            ->assertJsonPath('data.content', 'Komentar bagus');

        $reportResponse = $this->actingAs($user, 'sanctum')->postJson('/api/posts/'.$post->id.'/reports', [
            'reason' => 'Konten menyesatkan',
        ]);

        $reportResponse->assertStatus(201)
            ->assertJsonPath('data.reason', 'Konten menyesatkan');
    }
}
