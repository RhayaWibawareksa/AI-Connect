<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostVote;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class PostVoteController extends Controller
{
    /**
     * Catat atau ubah vote pada postingan (AJAX)
     */
    public function vote(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $userId = auth()->id();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        }

        $request->validate([
            'type' => 'required|in:up,down',
        ]);

        $type = $request->input('type');
        
        // Cari vote yang sudah ada
        $existingVote = PostVote::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        $newType = $type;

        if ($existingVote) {
            if ($existingVote->type === $type) {
                // Klik tombol yang sama lagi -> batalkan vote
                $existingVote->delete();
                $newType = null;
            } else {
                // Ubah arah vote (up ke down atau sebaliknya)
                $existingVote->update(['type' => $type]);
            }
        } else {
            // Buat vote baru
            PostVote::create([
                'user_id' => $userId,
                'post_id' => $post->id,
                'type' => $type,
            ]);
        }

        // Hitung ulang total suara post
        $upVotes = PostVote::where('post_id', $post->id)->where('type', 'up')->count();
        $downVotes = PostVote::where('post_id', $post->id)->where('type', 'down')->count();
        
        // Total suara = upvotes - downvotes
        $totalVotes = $upVotes - $downVotes;
        
        $post->update(['votes' => $totalVotes]);

        return response()->json([
            'success' => true,
            'votes' => $totalVotes,
            'user_vote' => $newType,
        ]);
    }

    /**
     * Tambahkan atau hapus postingan dari bookmark (AJAX)
     */
    public function bookmark(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $userId = auth()->id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        }

        $bookmark = Bookmark::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
        } else {
            Bookmark::create([
                'user_id' => $userId,
                'post_id' => $post->id,
            ]);
            $bookmarked = true;
        }

        return response()->json([
            'success' => true,
            'bookmarked' => $bookmarked,
        ]);
    }
}
