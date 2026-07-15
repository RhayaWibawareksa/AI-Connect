<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $alex = User::create([
            'name' => 'Alex',
            'username' => 'alex_dev',
            'email' => 'alex@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        $rina = User::create([
            'name' => 'Rina Setiawan',
            'username' => 'rina_ml',
            'email' => 'rina@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        $dimas = User::create([
            'name' => 'Dimas Pratama',
            'username' => 'dimas_nlp',
            'email' => 'dimas@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        $nadia = User::create([
            'name' => 'Nadia Kusuma',
            'username' => 'nadia_cv',
            'email' => 'nadia@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        $budi = User::create([
            'name' => 'Budi Hartono',
            'username' => 'budi_rl',
            'email' => 'budi@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        $sari = User::create([
            'name' => 'Sari Wulandari',
            'username' => 'sari_ds',
            'email' => 'sari@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'user',
        ]);

        $this->call(\Database\Seeders\AdminUserSeeder::class);

        // 2. Seed Categories
        $categories = [
            ['name' => 'Machine Learning', 'slug' => 'machine-learning'],
            ['name' => 'NLP & LLM', 'slug' => 'nlp-llm'],
            ['name' => 'Computer Vision', 'slug' => 'computer-vision'],
            ['name' => 'Reinforcement Learning', 'slug' => 'reinforcement-learning'],
            ['name' => 'Generative AI', 'slug' => 'generative-ai'],
            ['name' => 'Data Science', 'slug' => 'data-science'],
            ['name' => 'MLOps & Deploy', 'slug' => 'mlops-deploy'],
            ['name' => 'Etika AI', 'slug' => 'etika-ai'],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = \App\Models\Category::create($cat);
        }

        // 3. Seed Posts
        $post1 = \App\Models\Post::create([
            'user_id' => $rina->id,
            'category_id' => $catModels['machine-learning']->id,
            'title' => 'Implementasi Algoritma Gradient Boosting dari Nol — Panduan Lengkap untuk Pemula Absolut',
            'content' => "Halo komunitas! Kali ini saya ingin berbagi pengalaman saya mengimplementasikan algoritma Gradient Boosting (XGBoost-style) tanpa menggunakan library eksternal, murni menggunakan NumPy. Perjalanan ini mengajarkan saya banyak hal tentang decision tree, loss function, dan konsep boosting secara mendalam.

Kelebihan utama membangun algoritma dari awal adalah memahami matematika di balik bobot daun (leaf weights) dan bagaimana gradien kerugian menentukan arah penambahan pohon keputusan berikutnya. Artikel ini akan menjelaskan langkah demi langkah mulai dari pendefinisian Residuals, pembuatan regression tree dasar, penghitungan nilai output daun, hingga optimalisasi loss rate (shrinkage).",
            'github_url' => 'https://github.com/rina-setiawan/gradient-boosting-scratch',
            'votes' => 248,
            'status' => 'published',
            'created_at' => now()->subHours(2),
        ]);

        $post2 = \App\Models\Post::create([
            'user_id' => $dimas->id,
            'category_id' => $catModels['nlp-llm']->id,
            'title' => 'Fine-tuning GPT-2 untuk Bahasa Indonesia: Dataset, Tokenisasi, dan Hasil Evaluasi BLEU Score',
            'content' => "Proyek ini dimulai dari rasa penasaran: seberapa baik model LLM barat mampu di-fine-tune dengan korpus Bahasa Indonesia? Saya menggunakan dataset CC100-ID sebesar 1.2GB, melakukan custom tokenizer dengan SentencePiece, dan melatih ulang top-4 layer dari GPT-2 medium selama 3 epoch di Google Colab T4.

Hasilnya cukup mengejutkan! Dengan fine-tuning terarah, BLEU Score meningkat sebesar 18.4 poin dibandingkan base model tanpa fine-tuning. Evaluasi kualitatif juga menunjukkan koherensi bahasa Indonesia yang jauh lebih alami, meskipun model masih menunjukkan kecenderungan halusinasi pada prompt factual yang kompleks.",
            'github_url' => 'https://huggingface.co/dimas-pratama/gpt2-bahasa-indonesia',
            'demo_url' => 'https://colab.research.google.com',
            'votes' => 183,
            'status' => 'published',
            'created_at' => now()->subHours(5),
        ]);

        $post3 = \App\Models\Post::create([
            'user_id' => $nadia->id,
            'category_id' => $catModels['computer-vision']->id,
            'title' => 'Real-time Deteksi Gestur Tangan MediaPipe: Antarmuka Tanpa Sentuh untuk Presentasi',
            'content' => "Bayangkan mengontrol slide presentasimu hanya dengan gerakan tangan — tanpa remote, tanpa keyboard. Itulah yang saya bangun minggu ini. Dengan MediaPipe Hands dan OpenCV, saya berhasil mendeteksi 5 gestur unik (next, prev, zoom in, zoom out, stop) dengan akurasi 94.3% pada kondisi pencahayaan normal.

Implementasi ini menggunakan pendeteksian titik koordinat landmark tangan (21 landmark total) untuk menentukan kemiringan dan jarak jari. Kami juga mengoptimalkan waktu latensi pemrosesan frame hingga kurang dari 12ms per frame pada prosesor Core i5 generasi ke-10, sehingga pengalaman pengguna terasa instan dan bebas lag.",
            'github_url' => 'https://github.com/nadia-kusuma/gesture-control',
            'demo_url' => 'https://youtu.be/demo-link',
            'image_url' => 'true', // Flag untuk menampilkan placeholder visual demo di UI
            'votes' => 512,
            'status' => 'published',
            'created_at' => now()->subDay(),
        ]);

        $post4 = \App\Models\Post::create([
            'user_id' => $budi->id,
            'category_id' => $catModels['reinforcement-learning']->id,
            'title' => 'Melatih Agen Snake Game Menggunakan DQN (Deep Q-Network): Dari 0 Hingga Skor 500+',
            'content' => "Proyek klasik tapi selalu menarik! Saya melatih agen DQN untuk bermain Snake menggunakan PyTorch dengan epsilon-greedy exploration. Setelah 1000 episode, agen berhasil mencapai skor rata-rata 287 dan skor tertinggi 541. Artikel ini membahas arsitektur jaringan, replay buffer, dan trik training yang saya gunakan.

Tantangan terbesar dalam melatih Reinforcement Learning pada Snake adalah mendesain reward function yang seimbang. Reward positif terlalu besar untuk memakan buah memicu agen untuk menabrak badannya sendiri karena terburu-buru, sedangkan penalti menabrak yang terlalu kecil membuat agen malas belajar berputar menghindari tembok. Kami merumuskan formula reward dinamis yang memecahkan masalah ini.",
            'github_url' => 'https://github.com/budi-hartono/dqn-snake',
            'votes' => 319,
            'status' => 'published',
            'created_at' => now()->subDays(2),
        ]);

        // Postingan pending dilaporkan untuk Admin Panel
        $postPending = \App\Models\Post::create([
            'user_id' => null, // Anonymous user
            'category_id' => $catModels['nlp-llm']->id,
            'title' => 'Cara Bypass Filter Konten AI (Jailbreak Prompt LLM Terbaru)',
            'content' => "Di sini saya akan membagikan eksploitasi terbaru untuk membypass sensor keamanan pada model LLM seperti GPT-4 dan Claude. Dengan menyisipkan prompt penyamaran fiksi dan reverse roleplay, Anda bisa menanyakan resep membuat zat berbahaya secara ilegal...",
            'votes' => -14,
            'status' => 'pending',
            'created_at' => now()->subDay(),
        ]);

        // 4. Seed Comments
        // Comments for Post 1
        for ($i = 0; $i < 42; $i++) {
            \App\Models\Comment::create([
                'post_id' => $post1->id,
                'user_id' => ($i % 2 == 0) ? $dimas->id : $budi->id,
                'content' => 'Komentar dummy #' . ($i + 1) . ' untuk postingan Gradient Boosting. Sangat bermanfaat!',
                'created_at' => now()->subMinutes(10 * $i),
            ]);
        }

        // Comments for Post 2
        for ($i = 0; $i < 29; $i++) {
            \App\Models\Comment::create([
                'post_id' => $post2->id,
                'user_id' => ($i % 2 == 0) ? $rina->id : $nadia->id,
                'content' => 'Diskusi menarik #' . ($i + 1) . ' tentang Fine-tuning GPT-2 Bahasa Indonesia.',
                'created_at' => now()->subMinutes(15 * $i),
            ]);
        }

        // Comments for Post 3
        for ($i = 0; $i < 87; $i++) {
            \App\Models\Comment::create([
                'post_id' => $post3->id,
                'user_id' => ($i % 2 == 0) ? $alex->id : $sari->id,
                'content' => 'Keren sekali gestur MediaPipe ini! Komentar #' . ($i + 1),
                'created_at' => now()->subMinutes(5 * $i),
            ]);
        }

        // Comments for Post 4
        for ($i = 0; $i < 55; $i++) {
            \App\Models\Comment::create([
                'post_id' => $post4->id,
                'user_id' => ($i % 2 == 0) ? $rina->id : $dimas->id,
                'content' => 'Snake Game dengan DQN, referensi yang bagus #' . ($i + 1),
                'created_at' => now()->subMinutes(20 * $i),
            ]);
        }

        // 5. Seed Reports
        \App\Models\Report::create([
            'post_id' => $postPending->id,
            'reason' => 'Konten berbahaya dan melanggar hukum (jailbreak exploit)',
            'status' => 'pending',
        ]);

        // Simpan bookmark awal untuk Alex
        \App\Models\Bookmark::create([
            'user_id' => $alex->id,
            'post_id' => $post3->id,
        ]);

        // Simpan vote awal untuk Alex
        \App\Models\PostVote::create([
            'user_id' => $alex->id,
            'post_id' => $post1->id,
            'type' => 'up',
        ]);
    }
}
