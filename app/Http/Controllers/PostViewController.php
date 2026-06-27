<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostViewController extends Controller
{
    /**
     * Tampilkan halaman create post
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    /**
     * Simpan post ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'] ?? null,
            'user_id' => auth()->id() ?? 1, // Default ke user 1 jika belum login
            'status' => 'published',
        ]);

        return redirect('/dashboard')->with('success', 'Postingan berhasil dibuat!');
    }

    /**
     * Tampilkan detail post
     */
    public function show($id)
    {
        $post = Post::with(['user', 'category', 'comments.user'])->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Simpan komentar pada post
     */
    public function storeComment(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id() ?? 1, // Default ke user 1 jika belum login
        ]);

        return redirect()->route('posts.show', $id)->with('success', 'Komentar berhasil ditambahkan!');
    }
}
