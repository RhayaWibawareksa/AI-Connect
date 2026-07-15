<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Models\Report;
use App\Models\Notification;
use Illuminate\Http\Request;

class PostViewController extends Controller
{
    /**
     * Tampilkan dashboard feed dengan filter dan pencarian
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $query = Post::with(['user', 'category', 'comments', 'votesRelation', 'bookmarksRelation'])
            ->where('status', 'published');

        // 1. Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // 2. Filter Kategori
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // 3. Filter Navigasi Tabs (AJAX/Normal)
        $filter = $request->input('filter', 'popular'); // Default: Terpopuler
        
        if ($filter === 'new') {
            $query->latest();
        } elseif ($filter === 'discussed') {
            $query->withCount('comments')->orderBy('comments_count', 'desc');
        } elseif ($filter === 'saved') {
            if (!$userId) {
                $query->whereRaw('1 = 0'); // Jika belum login, kosongkan
            } else {
                $query->whereHas('bookmarksRelation', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            }
        } else {
            // popular -> sort by votes desc
            $query->orderBy('votes', 'desc')->latest();
        }

        // Paginate hasil postingan
        $posts = $query->paginate(5);

        // Jika request AJAX (dari filter tab atau load-more), kembalikan partial feed saja
        if ($request->ajax()) {
            return view('posts.partials.feed-loop', compact('posts'))->render();
        }

        // 4. Data Sidebar Kategori Populer (dynamic counts)
        $sidebarCategories = Category::withCount(['posts' => function($q) {
            $q->where('status', 'published');
        }])->get()->sortByDesc('posts_count')->take(8);

        // 5. Data Sidebar Kontributor (dihitung dinamis: post*10 + comment*5)
        $contributors = User::withCount(['posts', 'comments'])
            ->get()
            ->map(function ($u) {
                $u->score = ($u->posts_count * 10) + ($u->comments_count * 5);
                // Menentukan class background avatar agar warna-warni di UI
                $bgs = ['6366f1', '06b6d4', 'a78bfa', 'f59e0b', 'ef4444'];
                $u->avatar_bg = $bgs[abs(crc32($u->email)) % count($bgs)];
                return $u;
            })
            ->sortByDesc('score')
            ->take(5);

        // 6. Data Statistik Hero Banner
        $stats = [
            'total_threads' => Post::where('status', 'published')->count(),
            'total_members' => User::count(),
            'total_comments' => Comment::count(),
            // post baru 24 jam terakhir
            'new_posts' => Post::where('status', 'published')
                ->where('created_at', '>=', now()->subDay())
                ->count(),
            // simulasi anggota aktif online
            'active_members' => number_format(max(10, User::count() * 1.5 + rand(50, 150)))
        ];

        return view('dashboard', compact('posts', 'sidebarCategories', 'contributors', 'stats', 'filter'));
    }

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
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk membuat post');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'github_url' => 'nullable|url|max:255',
            'demo_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imageUrl = $request->file('image')->store('post-images', 'public');
        }

        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'] ?? null,
            'github_url' => $validated['github_url'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'image_url' => $imageUrl,
            'user_id' => auth()->id(), // Current authenticated user
            'status' => 'published',
            'votes' => 0,
        ]);

        return redirect('/dashboard')->with('success', 'Postingan berhasil dipublikasikan!');
    }

    /**
     * Tampilkan detail post
     */
    public function show($id)
    {
        $post = Post::with(['user', 'category', 'comments.user'])->findOrFail($id);
        
        // Ambil info vote user saat ini jika login
        $userVote = auth()->check() ? $post->userVoteType(auth()->id()) : null;
        $isBookmarked = auth()->check() ? $post->isBookmarkedBy(auth()->id()) : false;

        return view('posts.show', compact('post', 'userVote', 'isBookmarked'));
    }

    /**
     * Simpan komentar pada post
     */
    public function storeComment(Request $request, $id)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk memberikan komentar');
        }

        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(), // Current authenticated user
        ]);

        if ($post->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'comment',
                'data' => [
                    'commenter_id' => auth()->id(),
                    'commenter_name' => auth()->user()->name ?? 'Seseorang',
                    'post_id' => $post->id,
                    'post_title' => $post->title,
                    'comment_id' => $comment->id,
                ],
            ]);
        }

        return redirect()->route('posts.show', $id)->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Laporan postingan oleh user
     */
    public function report(Request $request, $id)
    {
        if (!auth()->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Silakan login terlebih dahulu untuk melaporkan.'], 401);
            }
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk melaporkan.');
        }

        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000'
        ]);

        $report = Report::create([
            'post_id' => $post->id,
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Laporan berhasil dikirim.', 'report_id' => $report->id]);
        }

        return redirect()->back()->with('success', 'Laporan berhasil dikirim. Terima kasih.');
    }
}
