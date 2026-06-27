<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category'])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar postingan berhasil dimuat',
            'data' => $posts,
        ], 200);
    }

    public function show($id)
    {
        $post = Post::with(['user', 'category', 'comments.user'])->find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $post,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status' => 'nullable|in:pending,published',
        ]);

        $post = Post::create([
            ...$validated,
            'user_id' => $request->user()?->id,
            'votes' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil dibuat',
            'data' => $post->load(['user', 'category']),
        ], 201);
    }

    public function storeComment(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => $request->user()?->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $comment->load('user'),
        ], 201);
    }

    public function storeReport(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        $report = $post->reports()->create([
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'data' => $report,
        ], 201);
    }
}