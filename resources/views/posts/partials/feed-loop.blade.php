@forelse ($posts as $post)
    @php
        $userVote = auth()->check() ? $post->userVoteType(auth()->id()) : null;
        $isBookmarked = auth()->check() ? $post->isBookmarkedBy(auth()->id()) : false;
        
        // Random bg color untuk avatar author
        $bgs = ['6366f1', '06b6d4', 'a78bfa', 'f59e0b', 'ef4444'];
        $authorBg = $post->user ? $bgs[abs(crc32($post->user->email)) % count($bgs)] : '64748b';
    @endphp

    <article class="post-card" data-post-id="{{ $post->id }}">
        {{-- Header: Avatar + Nama User + Kategori + Waktu --}}
        <div class="d-flex align-items-start gap-3 mb-3">
            <img
                src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'Anonim') }}&background={{ $authorBg }}&color=fff&size=64"
                alt="Avatar {{ $post->user->name ?? 'Anonim' }}"
                class="post-author-avatar"
            >
            <div class="flex-grow-1">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="#" class="post-author-name">{{ $post->user->name ?? 'Anonim' }}</a>
                    @if ($post->category)
                        <span class="badge-category">{{ $post->category->name }}</span>
                    @endif
                    <span class="post-meta-time">
                        <i class="bi bi-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            
            {{-- Tombol simpan/bookmark --}}
            <button 
                class="btn-share bookmark-btn" 
                onclick="handleBookmark(this, {{ $post->id }})" 
                title="Simpan postingan"
            >
                @if ($isBookmarked)
                    <i class="bi bi-bookmark-fill" style="color: #6366f1;"></i>
                @else
                    <i class="bi bi-bookmark"></i>
                @endif
            </button>
        </div>

        {{-- Judul Postingan --}}
        <a href="{{ route('posts.show', $post->id) }}" class="post-title">
            {{ $post->title }}
        </a>

        {{-- Isi Postingan (Preview) --}}
        <p class="post-body">
            {{ Str::limit($post->content, 260) }}
        </p>

        {{-- Menampilkan preview media jika ada image_url --}}
        @if ($post->image_url)
            @php
                $previewSrc = preg_match('/^https?:\/\//', $post->image_url)
                    ? $post->image_url
                    : \Illuminate\Support\Facades\Storage::disk('public')->url(ltrim($post->image_url, '/'));
            @endphp
            <div class="mb-3 rounded-3 overflow-hidden" style="background:#f1f5f9;">
                <img src="{{ $previewSrc }}" alt="Gambar Postingan" style="width:100%; height:180px; object-fit:cover; display:block;">
            </div>
        @endif

        {{-- Tombol Link Source Code / Demo --}}
        @if ($post->github_url || $post->demo_url)
            <div class="mb-3">
                @if ($post->github_url)
                    <a href="{{ $post->github_url }}" target="_blank" rel="noopener" class="btn-source-code">
                        @if (Str::contains($post->github_url, 'github.com'))
                            <i class="bi bi-github"></i> Lihat Source Code
                        @elseif (Str::contains($post->github_url, 'huggingface.co'))
                            <i class="bi bi-robot"></i> Lihat di HuggingFace
                        @else
                            <i class="bi bi-link-45deg"></i> Link Referensi
                        @endif
                    </a>
                @endif
                
                @if ($post->demo_url)
                    <a href="{{ $post->demo_url }}" target="_blank" rel="noopener" class="btn-source-code ms-2">
                        @if (Str::contains($post->demo_url, 'colab.research'))
                            <i class="bi bi-file-earmark-code"></i> Open in Colab
                        @elseif (Str::contains($post->demo_url, 'youtube.com') || Str::contains($post->demo_url, 'youtu.be'))
                            <i class="bi bi-play-circle"></i> Tonton Demo
                        @else
                            <i class="bi bi-play-circle"></i> Demo Online
                        @endif
                    </a>
                @endif
            </div>
        @endif

        {{-- Action Bar: Vote + Komentar + Share --}}
        <div class="post-action-bar">
            {{-- Grup Upvote / Downvote --}}
            <div class="vote-group">
                <button
                    class="btn-vote @if($userVote === 'up') upvoted @endif"
                    onclick="handleVote(this, {{ $post->id }}, 'up')"
                    aria-label="Upvote postingan"
                    title="Upvote"
                >
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </button>
                <span class="vote-count" id="vote-count-{{ $post->id }}">{{ $post->votes }}</span>
                <div class="vote-divider"></div>
                <button
                    class="btn-vote @if($userVote === 'down') downvoted @endif"
                    onclick="handleVote(this, {{ $post->id }}, 'down')"
                    aria-label="Downvote postingan"
                    title="Downvote"
                >
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </button>
            </div>

            {{-- Tombol Komentar --}}
            <a href="{{ route('posts.show', $post->id) }}" class="btn-comment">
                <i class="bi bi-chat-dots"></i> {{ $post->comments->count() }} Komentar
            </a>

            {{-- Tombol Share --}}
            <button class="btn-share ms-auto" onclick="navigator.clipboard.writeText('{{ route('posts.show', $post->id) }}'); alert('Tautan postingan berhasil disalin ke clipboard!');" title="Bagikan">
                <i class="bi bi-share"></i> Bagikan
            </button>
            
            {{-- Tombol Laporkan --}}
            <button class="btn-share ms-2 text-danger" onclick="window.reportPost({{ $post->id }})" title="Laporkan postingan">
                <i class="bi bi-flag"></i> Laporkan
            </button>
        </div>
    </article>
@empty
    <div class="text-center py-5 bg-white border rounded-3 text-muted">
        <i class="bi bi-chat-square-text fs-1 mb-2 d-block" style="color: #cbd5e1;"></i>
        <p class="mb-0">Tidak ada postingan yang ditemukan.</p>
    </div>
@endforelse

{{-- Link Pagination (Hidden, dibaca oleh JavaScript load more) --}}
@if ($posts->hasMorePages())
    <div id="next-page-link" class="d-none" data-url="{{ $posts->nextPageUrl() }}"></div>
@endif
