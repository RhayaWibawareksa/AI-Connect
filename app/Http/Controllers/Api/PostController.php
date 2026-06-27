<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Mengambil daftar postingan dengan relasi user dan category.
     */
    public function index()
    {
        // Mengambil data dari database dengan relasi
        $posts = Post::with(['user', 'category'])->latest()->get();

        // Mengembalikan respon dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Daftar postingan berhasil dimuat',
            'data'    => $posts
        ], 200);
    }

    /**
     * Mengambil detail satu postingan berdasarkan ID.
     */
    public function show($id)
    {
        $post = Post::with(['user', 'category'])->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $post
        ], 200);
    }
}