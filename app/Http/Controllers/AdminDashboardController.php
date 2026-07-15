<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Report;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! $user->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses admin diperlukan untuk melihat halaman ini.');
        }

        $totalUsers = User::count();
        $usersWithPosts = User::has('posts')->count();
        $usersWithoutPosts = max(0, $totalUsers - $usersWithPosts);

        $stats = [
            'total_users' => $totalUsers,
            'users_with_posts' => $usersWithPosts,
            'users_without_posts' => $usersWithoutPosts,
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'pending_posts' => Post::where('status', 'pending')->count(),
        ];

        $recentPosts = Post::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $recentUsers = User::withCount('posts')
            ->latest()
            ->take(10)
            ->get();

        $recentComments = \App\Models\Comment::with(['user', 'post'])
            ->latest()
            ->take(10)
            ->get();

        $categories = \App\Models\Category::withCount('posts')
            ->orderByDesc('posts_count')
            ->get();

        $reports = \App\Models\Report::with('post')
            ->latest()
            ->take(10)
            ->get();

        $blockedUsers = User::where('role', 'banned')->get();

        // Analytics: configurable days (7 or 30)
        $allowed = [7, 30];
        $days = (int) $request->input('analytics_days', 7);
        if (! in_array($days, $allowed)) $days = 7;

        $labels = [];
        $postsCounts = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $labels[] = $d->format('d M');
            $postsCounts[] = Post::whereDate('created_at', $d->toDateString())->count();
        }

        $topCategories = \App\Models\Category::withCount(['posts' => function($q){ $q->where('status','published'); }])
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        $topAuthors = User::withCount('posts')
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        $analytics = [
            'labels' => $labels,
            'posts' => $postsCounts,
            'topCategories' => $topCategories,
            'topAuthors' => $topAuthors,
        ];

        return view('admin', compact('stats', 'recentPosts', 'recentUsers', 'recentComments', 'categories', 'reports', 'blockedUsers', 'analytics'));
    }

    public function unblock($id)
    {
        $user = User::find($id);
        if (! $user) {
            return redirect()->route('admin.secret')->with('error', 'User tidak ditemukan');
        }

        // Ubah role menjadi 'user' sebagai default
        $user->role = 'user';
        $user->save();

        return redirect()->route('admin.secret')->with('success', 'User telah di-unblock');
    }

    // Moderation: delete the reported post
    public function deleteReportedPost($id)
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses admin diperlukan.');
        }

        $report = Report::find($id);
        if (! $report) {
            return redirect()->route('admin.secret')->with('error', 'Laporan tidak ditemukan.');
        }

        $post = $report->post;
        if ($post) {
            $post->delete();
        }

        $report->status = 'resolved';
        $report->save();

        return redirect()->route('admin.secret')->with('success', 'Postingan telah dihapus dan laporan diselesaikan.');
    }

    // Moderation: ban the author of the reported post (or error if unknown)
    public function banReportedUser($id)
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses admin diperlukan.');
        }

        $report = Report::find($id);
        if (! $report) {
            return redirect()->route('admin.secret')->with('error', 'Laporan tidak ditemukan.');
        }

        $post = $report->post;
        $author = $post?->user;
        if (! $author) {
            return redirect()->route('admin.secret')->with('error', 'Penulis tidak ditemukan untuk laporan ini.');
        }

        $author->role = 'banned';
        $author->save();

        $report->status = 'resolved';
        $report->save();

        return redirect()->route('admin.secret')->with('success', 'User telah dibanned dan laporan diselesaikan.');
    }

    // Moderation: dismiss/ignore the report
    public function dismissReport($id)
    {
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            return redirect('/dashboard')->with('error', 'Akses admin diperlukan.');
        }

        $report = Report::find($id);
        if (! $report) {
            return redirect()->route('admin.secret')->with('error', 'Laporan tidak ditemukan.');
        }

        $report->status = 'ignored';
        $report->save();

        return redirect()->route('admin.secret')->with('success', 'Laporan telah diabaikan.');
    }

}
