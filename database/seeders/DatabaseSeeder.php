<?php

namespace Database\Seeders;

use App\Enums\AnswerStatus;
use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use App\Enums\PageStatus;
use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionTopic;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin & Editor Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
                'email_verified_at' => now(),
            ]
        );

        $editor = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Staf Editor',
                'password' => Hash::make('password'),
                'role' => UserRole::Editor,
                'email_verified_at' => now(),
            ]
        );

        // 2. Settings
        $settings = [
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Portal Resmi Instansi Pemerintah', 'type' => 'text'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Melayani dengan Cepat, Akurat, dan Transparan', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_email', 'value' => 'layanan@instansi.go.id', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_phone', 'value' => '(021) 1234-5678', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_whatsapp', 'value' => '081234567890', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_address', 'value' => 'Jl. Merdeka No. 45, Jakarta Pusat, DKI Jakarta 10110', 'type' => 'textarea'],
            ['group' => 'social_media', 'key' => 'social_facebook', 'value' => 'https://facebook.com/instansiresmi', 'type' => 'text'],
            ['group' => 'social_media', 'key' => 'social_instagram', 'value' => 'https://instagram.com/instansiresmi', 'type' => 'text'],
            ['group' => 'social_media', 'key' => 'social_youtube', 'value' => 'https://youtube.com/@instansiresmi', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Categories
        $categories = [
            [
                'name' => 'Berita Utama',
                'slug' => 'berita-utama',
                'description' => 'Kumpulan berita resmi dan siaran pers terbaru.',
                'icon' => 'heroicon-o-newspaper',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman kedinasan, seleksi, dan kebijakan penting.',
                'icon' => 'heroicon-o-speakerphone',
                'sort_order' => 2,
            ],
            [
                'name' => 'Agenda Kegiatan',
                'slug' => 'agenda-kegiatan',
                'description' => 'Jadwal dan dokumentasi kegiatan resmi pimpinan instansi.',
                'icon' => 'heroicon-o-calendar',
                'sort_order' => 3,
            ],
            [
                'name' => 'Layanan & Kebijakan',
                'slug' => 'layanan-kebijakan',
                'description' => 'Informasi seputar regulasi dan panduan layanan publik.',
                'icon' => 'heroicon-o-clipboard-document-list',
                'sort_order' => 4,
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 4. Sample Posts
        $posts = [
            [
                'user_id' => $admin->id,
                'category_id' => $categoryModels['berita-utama']->id,
                'title' => 'Peningkatan Mutu Layanan Publik Berbasis Digital Melalui Aplikasi Terpadu',
                'slug' => 'peningkatan-mutu-layanan-publik-berbasis-digital',
                'summary' => 'Instansi meluncurkan inovasi layanan publik berbasis teknologi digital untuk memudahkan akses warga secara real-time.',
                'content' => '<p>Dalam rangka mempercepat transformasi birokrasi, instansi resmi mengumumkan peluncuran portal layanan publik digital. Sistem ini memungkinkan warga untuk mengajukan pertanyaan, memantau pengaduan, dan membaca update berita resmi secara mudah melalui ponsel pintar.</p><p>Pimpinan instansi menegaskan komitmen seluruh jajaran dalam memberikan respon prima dan transparan bagi setiap pertanyaan yang diajukan masyarakat.</p>',
                'status' => PostStatus::Published,
                'published_at' => now()->subDays(2),
                'view_count' => 142,
                'is_featured' => true,
            ],
            [
                'user_id' => $editor->id,
                'category_id' => $categoryModels['pengumuman']->id,
                'title' => 'Jadwal Operasional Layanan Tatap Muka dan Layanan Daring Pekan Ini',
                'slug' => 'jadwal-operasional-layanan-tatap-muka-dan-daring',
                'summary' => 'Informasi jam operasional loket layanan publik tatap muka dan kanal konsultasi digital.',
                'content' => '<p>Diberitahukan kepada seluruh masyarakat bahwa loket pelayanan tatap muka beroperasi setiap hari Senin - Jumat pukul 08.00 s/d 15.30 WIB. Untuk konsultasi dan pertanyaan online dapat dikirimkan sewaktu-waktu selama 24 jam melalui aplikasi ini.</p>',
                'status' => PostStatus::Published,
                'published_at' => now()->subDay(),
                'view_count' => 88,
                'is_featured' => false,
            ],
            [
                'user_id' => $admin->id,
                'category_id' => $categoryModels['layanan-kebijakan']->id,
                'title' => 'Panduan Pengajuan Pertanyaan dan Informasi Terbuka Bagi Warga',
                'slug' => 'panduan-pengajuan-pertanyaan-dan-informasi-terbuka',
                'summary' => 'Langkah-langkah menyampaikan aspirasi dan pertanyaan melalui sistem tanya jawab online.',
                'content' => '<p>Setiap masyarakat berhak memperoleh informasi publik yang transparan. Ikuti panduan sederhana ini: pilih topik yang relevan, tuliskan uraian pertanyaan secara jelas, dan masukkan alamat email aktif agar notifikasi jawaban dapat diterima secara langsung.</p>',
                'status' => PostStatus::Published,
                'published_at' => now(),
                'view_count' => 56,
                'is_featured' => true,
            ],
        ];

        foreach ($posts as $postData) {
            Post::firstOrCreate(['slug' => $postData['slug']], $postData);
        }

        // 5. Static Pages
        $pages = [
            [
                'user_id' => $admin->id,
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'content' => '<h2>Profil Singkat</h2><p>Kami adalah instansi pemerintah yang bertugas memberikan pelayanan prima, transparan, dan profesional bagi masyarakat luas. Didukung oleh teknologi informasi terkini, kami terus bertransformasi menuju pemerintahan berbasis digital yang akuntabel.</p>',
                'status' => PageStatus::Published,
                'sort_order' => 1,
                'show_in_menu' => true,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Visi & Misi',
                'slug' => 'visi-misi',
                'content' => '<h2>Visi</h2><p>Menjadi instansi terdepan dalam pelayanan publik yang inklusif, akuntabel, dan berdaya saing tinggi.</p><h2>Misi</h2><ul><li>Meningkatkan kualitas pelayanan yang cepat dan tepat sasaran.</li><li>Mendorong partisipasi aktif masyarakat dalam pembangunan.</li><li>Mewujudkan tata kelola pemerintahan yang bersih dan berintegritas.</li></ul>',
                'status' => PageStatus::Published,
                'sort_order' => 2,
                'show_in_menu' => true,
            ],
            [
                'user_id' => $admin->id,
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'content' => '<h2>Bagan Organisasi</h2><p>Struktur kelembagaan terdiri dari Pimpinan Kepala Instansi, Sekretariat, dan Bidang-Bidang Teknis Pelayanan yang siap melayani kebutuhan informasi Anda.</p>',
                'status' => PageStatus::Published,
                'sort_order' => 3,
                'show_in_menu' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::firstOrCreate(['slug' => $pageData['slug']], $pageData);
        }

        // 6. Slides
        $slides = [
            [
                'title' => 'Selamat Datang di Portal Resmi & Layanan Aspirasi',
                'description' => 'Akses informasi publik, berita terkini, dan konsultasi interaktif kapan saja.',
                'image' => 'slides/banner-1.jpg',
                'link_url' => '/tentang-kami',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Punya Pertanyaan atau Masukan? Kami Siap Menjawab',
                'description' => 'Gunakan fitur Kirim Pertanyaan untuk berkomunikasi langsung dengan petugas instansi.',
                'image' => 'slides/banner-2.jpg',
                'link_url' => '/tanya-kami',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slideData) {
            Slide::firstOrCreate(['title' => $slideData['title']], $slideData);
        }

        // 7. Navigation Menus & Items
        $headerMenu = Menu::firstOrCreate(
            ['location' => MenuLocation::Header],
            ['name' => 'Menu Utama Header', 'is_active' => true]
        );

        $menuItems = [
            ['menu_id' => $headerMenu->id, 'label' => 'Beranda', 'url' => '/', 'type' => MenuItemType::Custom, 'sort_order' => 1],
            ['menu_id' => $headerMenu->id, 'label' => 'Tentang Kami', 'url' => '/pages/tentang-kami', 'type' => MenuItemType::Page, 'sort_order' => 2],
            ['menu_id' => $headerMenu->id, 'label' => 'Visi & Misi', 'url' => '/pages/visi-misi', 'type' => MenuItemType::Page, 'sort_order' => 3],
            ['menu_id' => $headerMenu->id, 'label' => 'Berita', 'url' => '/posts', 'type' => MenuItemType::Custom, 'sort_order' => 4],
            ['menu_id' => $headerMenu->id, 'label' => 'Tanya Kami', 'url' => '/pertanyaan', 'type' => MenuItemType::Custom, 'sort_order' => 5],
            ['menu_id' => $headerMenu->id, 'label' => 'FAQ', 'url' => '/faq', 'type' => MenuItemType::Custom, 'sort_order' => 6],
        ];

        foreach ($menuItems as $item) {
            MenuItem::firstOrCreate(['menu_id' => $item['menu_id'], 'label' => $item['label']], $item);
        }

        // 8. Question Topics
        $topics = [
            [
                'name' => 'Pelayanan & Perizinan',
                'slug' => 'pelayanan-perizinan',
                'description' => 'Pertanyaan seputar syarat, tata cara, dan alur perizinan atau administrasi.',
                'icon' => 'heroicon-o-document-check',
                'sort_order' => 1,
            ],
            [
                'name' => 'Pengaduan & Saran',
                'slug' => 'pengaduan-saran',
                'description' => 'Aspirasi, laporan kendala di lapangan, atau kritik dan saran membangun.',
                'icon' => 'heroicon-o-megaphone',
                'sort_order' => 2,
            ],
            [
                'name' => 'Informasi Umum & Regulasi',
                'slug' => 'informasi-umum-regulasi',
                'description' => 'Pertanyaan mengenai aturan, dasar hukum, dan jadwal kegiatan instansi.',
                'icon' => 'heroicon-o-information-circle',
                'sort_order' => 3,
            ],
        ];

        $topicModels = [];
        foreach ($topics as $t) {
            $topicModels[$t['slug']] = QuestionTopic::firstOrCreate(['slug' => $t['slug']], $t);
        }

        // 9. Questions & Answers
        $q1 = Question::firstOrCreate(
            ['email' => 'budi.santoso@example.com', 'subject' => 'Berapa lama jangka waktu penerbitan rekomendasi teknis?'],
            [
                'topic_id' => $topicModels['pelayanan-perizinan']->id,
                'name' => 'Budi Santoso',
                'phone' => '081299887766',
                'body' => 'Selamat siang, saya ingin bertanya berapa lama estimasi waktu penyelesaian rekomendasi teknis apabila semua berkas persyaratan sudah dinyatakan lengkap?',
                'status' => QuestionStatus::Answered,
                'ip_address' => '127.0.0.1',
            ]
        );

        QuestionAnswer::firstOrCreate(
            ['question_id' => $q1->id],
            [
                'user_id' => $admin->id,
                'body' => '<p>Halo Bapak Budi Santoso, terima kasih atas pertanyaannya. Sesuai standar operasional pelayanan kami, penerbitan rekomendasi teknis diselesaikan paling lambat 3 (tiga) hari kerja sejak berkas diverifikasi lengkap dan valid.</p>',
                'status' => AnswerStatus::Published,
                'is_published' => true,
                'answered_at' => now()->subHours(5),
            ]
        );

        Question::firstOrCreate(
            ['email' => 'siti.rahma@example.com', 'subject' => 'Apakah layanan konsultasi tatap muka perlu reservasi antrean?'],
            [
                'topic_id' => $topicModels['pelayanan-perizinan']->id,
                'name' => 'Siti Rahma',
                'phone' => '085712344321',
                'body' => 'Apakah pemohon harus mengambil nomor antrean online terlebih dahulu sebelum datang ke kantor, atau bisa langsung datang saat jam kerja?',
                'status' => QuestionStatus::Pending,
                'ip_address' => '127.0.0.1',
            ]
        );

        // 10. FAQs
        $faqs = [
            [
                'topic_id' => $topicModels['pelayanan-perizinan']->id,
                'question' => 'Apakah pengurusan dokumen di loket pelayanan dipungut biaya?',
                'answer' => '<p>Semua layanan administrasi dan konsultasi pada instansi ini <strong>gratis / tidak dipungut biaya apapun (Rp 0)</strong>. Mohon waspada terhadap pihak tidak bertanggung jawab yang mengatasnamakan petugas.</p>',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'topic_id' => $topicModels['pelayanan-perizinan']->id,
                'question' => 'Bagaimana cara melacak perkembangan pengajuan berkas saya?',
                'answer' => '<p>Anda dapat melacak status pengajuan menggunakan nomor registrasi melalui menu pencarian di portal ini atau menghubungi nomor hotline resmi kami.</p>',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'topic_id' => $topicModels['pengaduan-saran']->id,
                'question' => 'Apakah identitas penanya atau pengadu dijamin kerahasiaannya?',
                'answer' => '<p>Ya, instansi menjamin kerahasiaan data pribadi penanya sesuai dengan ketentuan undang-undang perlindungan data pribadi dan keterbukaan informasi publik.</p>',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::firstOrCreate(['question' => $faqData['question']], $faqData);
        }
    }
}
