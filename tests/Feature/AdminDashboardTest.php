<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_account_and_posting_statistics(): void
    {
        $userWithPost = User::factory()->create([
            'name' => 'Alicia',
            'email' => 'alicia@example.com',
        ]);

        $userWithoutPost = User::factory()->create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
        ]);

        Post::create([
            'title' => 'Postingan pertama',
            'content' => 'Isi postingan',
            'user_id' => $userWithPost->id,
            'status' => 'published',
            'votes' => 0,
        ]);

        $response = $this->get('/admin-secret');

        $response->assertStatus(200);
        $response->assertSee('Total akun terdaftar');
        $response->assertSee('Sudah membuat postingan');
        $response->assertSee('Belum pernah posting');
        $response->assertSee('2');
        $response->assertSee('1');
    }
}
