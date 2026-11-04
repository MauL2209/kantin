<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Stand;
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
        // 1. Akun Pengguna (Admin & Siswa)
        $admin = User::create([
            'name' => 'Administrator Kantin',
            'email' => 'admin@melatiarea.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'class_room' => 'Staf Tata Usaha',
        ]);

        $siswa1 = User::create([
            'name' => 'Muhammad Rizky Pratama',
            'email' => 'siswa@melatiarea.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nis' => '20241001',
            'class_room' => 'XII RPL 1',
            'phone' => '085712345678',
        ]);

        $siswa2 = User::create([
            'name' => 'Siti Nur Aisyah',
            'email' => 'siti@melatiarea.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nis' => '20241002',
            'class_room' => 'XI Bisnis Digital 2',
            'phone' => '087812345678',
        ]);

        $siswa3 = User::create([
            'name' => 'Dimas Anggara',
            'email' => 'dimas@melatiarea.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
            'nis' => '20241003',
            'class_room' => 'X Akuntansi 1',
            'phone' => '089612345678',
        ]);

        // 2. Kategori Produk
        $categoriesData = [
            ['name' => 'Makanan', 'slug' => 'makanan', 'icon' => 'utensils', 'description' => 'Aneka makanan berat dan nasi lezat bergizi'],
            ['name' => 'Minuman', 'slug' => 'minuman', 'icon' => 'coffee', 'description' => 'Minuman dingin segar dan aneka seduhan hangat'],
            ['name' => 'Snack', 'slug' => 'snack', 'icon' => 'cookie', 'description' => 'Cemilan gurih, gorengan hangat, dan kudapan renyah'],
            ['name' => 'Mie', 'slug' => 'mie', 'icon' => 'soup', 'description' => 'Olahan mie goreng, kuah nyemek, dan bakmi spesial'],
            ['name' => 'Dessert', 'slug' => 'dessert', 'icon' => 'ice-cream', 'description' => 'Kue manis, puding lembut, dan sajian pencuci mulut'],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::create($c);
        }

        // 3. 8 Stand Kantin (Masing-masing dari 8 Jurusan)
        $standsData = [
            [
                'name' => 'Stand Bisnis Digital',
                'department' => 'Bisnis Digital',
                'description' => 'Menyediakan hidangan kekinian, aneka racikan kopi modern, dan cemilan viral karya wirausaha siswa jurusan Bisnis Digital.',
                'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona A - Stand No. 01',
                'contact_person' => 'Bu Ratna (0812-1001-001)',
            ],
            [
                'name' => 'Stand Manajemen Logistik',
                'department' => 'Manajemen Logistik',
                'description' => 'Sajian cepat saji berkualitas, higienis, dan terdistribusi cepat dari siswa jurusan Manajemen Logistik.',
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona A - Stand No. 02',
                'contact_person' => 'Pak Hendra (0812-1001-002)',
            ],
            [
                'name' => 'Stand Layanan Perbankan Syariah',
                'department' => 'Layanan Perbankan Syariah',
                'description' => 'Makanan dan minuman halal, thayyib, berkah, dengan cita rasa autentik dan kebersihan terjamin.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona B - Stand No. 03',
                'contact_person' => 'Ibu Farida (0812-1001-003)',
            ],
            [
                'name' => 'Stand Akuntansi 1',
                'department' => 'Akuntansi 1',
                'description' => 'Paket makan hemat, lezat, terhitung presisi dengan porsi pas dan bersahabat bagi kantong seluruh siswa.',
                'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona B - Stand No. 04',
                'contact_person' => 'Bu Dewi (0812-1001-004)',
            ],
            [
                'name' => 'Stand Bisnis Retail',
                'department' => 'Bisnis Retail',
                'description' => 'Pusat jajanan serba ada, snack kemasan premium, minuman segar dingin, dan frozen food siap santap.',
                'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona C - Stand No. 05',
                'contact_person' => 'Pak Bambang (0812-1001-005)',
            ],
            [
                'name' => 'Stand Manajemen Perkantoran',
                'department' => 'Manajemen Perkantoran',
                'description' => 'Menu elegan, bento box rapi, sandwich sehat, dan mocktail segar dengan pelayanan ramah berstandar perkantoran.',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona C - Stand No. 06',
                'contact_person' => 'Bu Sri (0812-1001-006)',
            ],
            [
                'name' => 'Stand Rekayasa Perangkat Lunak',
                'department' => 'Rekayasa Perangkat Lunak',
                'description' => 'Berbagai pilihan makanan dan minuman energi tinggi yang dikelola oleh siswa jurusan Rekayasa Perangkat Lunak.',
                'image' => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona D - Stand No. 07 (Tech Corner)',
                'contact_person' => 'Pak Dian (0812-1001-007)',
            ],
            [
                'name' => 'Stand Akuntansi 2',
                'department' => 'Akuntansi 2',
                'description' => 'Menu nusantara bergizi seimbang, aneka jus buah murni, dan dessert manis dengan perhitungan nilai gizi tepat.',
                'image' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=600&auto=format&fit=crop&q=80',
                'status' => 'open',
                'location' => 'Zona D - Stand No. 08',
                'contact_person' => 'Bu Fitri (0812-1001-008)',
            ],
        ];

        $stands = [];
        foreach ($standsData as $index => $s) {
            $stands[$index + 1] = Stand::create($s);
        }

        // 4. Produk-Produk Tiap Stand (Minimal 3 - 5 produk per stand)
        $productsData = [
            // Stand 1: Bisnis Digital
            [
                'stand_id' => 1,
                'category_id' => $categories['minuman']->id,
                'name' => 'Kopi Susu Gula Aren Digital',
                'description' => 'Espresso arabika dipadu susu segar lembut dan manis legitnya gula aren murni.',
                'price' => 12000,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=500&auto=format&fit=crop&q=80',
                'is_featured' => true,
            ],
            [
                'stand_id' => 1,
                'category_id' => $categories['dessert']->id,
                'name' => 'Croissant Coklat Almond',
                'description' => 'Pastry renyah berlapis dengan isian coklat lumer bertabur kacang almond panggang.',
                'price' => 15000,
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'stand_id' => 1,
                'category_id' => $categories['makanan']->id,
                'name' => 'Rice Bowl Chicken Teriyaki',
                'description' => 'Nasi pulen hangat dengan potongan daging ayam fillet berbalut saus teriyaki gurih manis.',
                'price' => 18000,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'stand_id' => 1,
                'category_id' => $categories['snack']->id,
                'name' => 'Kentang Goreng Curly Cheese',
                'description' => 'French fries renyah bentuk keriting dengan taburan bumbu keju cheddar spesial.',
                'price' => 10000,
                'stock' => 30,
                'is_featured' => false,
            ],

            // Stand 2: Manajemen Logistik
            [
                'stand_id' => 2,
                'category_id' => $categories['mie']->id,
                'name' => 'Mie Goreng Komplit Logistik',
                'description' => 'Mie goreng bumbu tradisional dengan topping bakso sapi, sosis, sayuran, dan telur ceplok.',
                'price' => 13000,
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'stand_id' => 2,
                'category_id' => $categories['makanan']->id,
                'name' => 'Nasi Uduk Ayam Serundeng',
                'description' => 'Nasi gurih harum kelapa disajikan lengkap dengan ayam goreng serundeng, sambal terasi, dan lalapan.',
                'price' => 16000,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'stand_id' => 2,
                'category_id' => $categories['minuman']->id,
                'name' => 'Es Lemon Tea Jumbo',
                'description' => 'Teh hitam segar diseduh dengan sari lemon segar dan es batu dingin melegakan tenggorokan.',
                'price' => 6000,
                'stock' => 50,
                'is_featured' => false,
            ],
            [
                'stand_id' => 2,
                'category_id' => $categories['snack']->id,
                'name' => 'Cireng Crispy Bumbu Rujak',
                'description' => 'Aci digoreng garing di luar kenyal di dalam, dicocol dengan kuah sambal rujak asam pedas manis.',
                'price' => 8000,
                'stock' => 15,
                'is_featured' => false,
            ],

            // Stand 3: Layanan Perbankan Syariah
            [
                'stand_id' => 3,
                'category_id' => $categories['makanan']->id,
                'name' => 'Nasi Kebuli Ayam Syariah',
                'description' => 'Nasi beraroma rempah kapulaga dan cengkeh khas timur tengah, disajikan dengan ayam panggang wangi.',
                'price' => 20000,
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'stand_id' => 3,
                'category_id' => $categories['dessert']->id,
                'name' => 'Roti Maryam Coklat Keju',
                'description' => 'Roti pipih gurih berserat lembut dipanggang butter dan diberi topping meises coklat serta keju parut.',
                'price' => 12000,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'stand_id' => 3,
                'category_id' => $categories['minuman']->id,
                'name' => 'Teh Tarik Rempah Hangat',
                'description' => 'Seduhan teh pekat berbusa tebal dengan susu kental manis dan aroma kayu manis menenangkan.',
                'price' => 8000,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'stand_id' => 3,
                'category_id' => $categories['snack']->id,
                'name' => 'Samosa Daging Sapi Gurih (3 pcs)',
                'description' => 'Pastry segitiga kulit renyah berisi cincangan daging sapi berbumbu kari lezat.',
                'price' => 10000,
                'stock' => 18,
                'is_featured' => false,
            ],

            // Stand 4: Akuntansi 1
            [
                'stand_id' => 4,
                'category_id' => $categories['makanan']->id,
                'name' => 'Nasi Ayam Geprek Sambal Bawang',
                'description' => 'Ayam krispi digeprek dengan cabai rawit pedas segar dan bawang putih harum, disajikan bersama nasi putih pulen.',
                'price' => 15000,
                'stock' => 30,
                'is_featured' => true,
            ],
            [
                'stand_id' => 4,
                'category_id' => $categories['mie']->id,
                'name' => 'Mie Nyemek Pedas Level 1-5',
                'description' => 'Mie kuah sedikit kental bertabur suwiran ayam, kubis, tomat segar, dan telur orak-arik lezat.',
                'price' => 12000,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'stand_id' => 4,
                'category_id' => $categories['minuman']->id,
                'name' => 'Es Jeruk Peras Asli',
                'description' => 'Perasan jeruk asli murni tanpa pemanis buatan, manis asam segar pelepas dahaga.',
                'price' => 6000,
                'stock' => 40,
                'is_featured' => false,
            ],
            [
                'stand_id' => 4,
                'category_id' => $categories['snack']->id,
                'name' => 'Pisang Bakar Coklat Keju',
                'description' => 'Pisang uli manis dibakar harum dengan kucuran susu kental manis, coklat, dan parutan keju gurih.',
                'price' => 9000,
                'stock' => 0, // Contoh stok 0 untuk verifikasi badge Habis
                'is_featured' => false,
            ],

            // Stand 5: Bisnis Retail
            [
                'stand_id' => 5,
                'category_id' => $categories['makanan']->id,
                'name' => 'Bento Box Beef Yakiniku',
                'description' => 'Daging sapi iris tipis juicy saus yakiniku manis gurih, disajikan dengan nasi jepang dan salad renyah.',
                'price' => 22000,
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'stand_id' => 5,
                'category_id' => $categories['snack']->id,
                'name' => 'Corndog Mozzarella Full Tarik',
                'description' => 'Sosis dibalut keju mozzarella premium berbalur tepung renyah keemasan bertabur saus mayones.',
                'price' => 12000,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'stand_id' => 5,
                'category_id' => $categories['minuman']->id,
                'name' => 'Boba Brown Sugar Fresh Milk',
                'description' => 'Boba tapioka kenyal lembut yang dimasak karamel gula aren cair dengan susu sapi murni dingin.',
                'price' => 12000,
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'stand_id' => 5,
                'category_id' => $categories['dessert']->id,
                'name' => 'Puding Coklat Saus Vla Vanilla',
                'description' => 'Puding coklat hitam lembut meleleh di mulut dengan siraman vla susu vanilla creamy.',
                'price' => 8000,
                'stock' => 15,
                'is_featured' => false,
            ],

            // Stand 6: Manajemen Perkantoran
            [
                'stand_id' => 6,
                'category_id' => $categories['makanan']->id,
                'name' => 'Club Sandwich Tuna & Egg',
                'description' => 'Roti gandum bakar lapis tiga berisi tuna flakes gurih, telur mata sapi, selada air, dan irisan tomat.',
                'price' => 16000,
                'stock' => 18,
                'is_featured' => false,
            ],
            [
                'stand_id' => 6,
                'category_id' => $categories['snack']->id,
                'name' => 'Dimsum Ayam Udang Kukus (4 pcs)',
                'description' => 'Siomay dimsum daging ayam dan udang juicy disajikan panas dengan chili oil pedas sedap.',
                'price' => 15000,
                'stock' => 25,
                'is_featured' => true,
            ],
            [
                'stand_id' => 6,
                'category_id' => $categories['minuman']->id,
                'name' => 'Lychee Tea Breeze Segar',
                'description' => 'Teh aromatik wangi dipadukan sirup leci manis dan buah leci utuh segar menyegarkan hari.',
                'price' => 10000,
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'stand_id' => 6,
                'category_id' => $categories['dessert']->id,
                'name' => 'Salad Buah Saus Mayo Keju',
                'description' => 'Potongan melon, apel fuji, anggur, dan jelly kenyal dengan dressing yogurt keju melimpah.',
                'price' => 12000,
                'stock' => 12,
                'is_featured' => false,
            ],

            // Stand 7: Rekayasa Perangkat Lunak
            [
                'stand_id' => 7,
                'category_id' => $categories['makanan']->id,
                'name' => 'Nasi Goreng Spesial RPL Fullstack',
                'description' => 'Nasi goreng racikan istimewa siswa RPL dengan isian sosis, ayam suwir, telur dadar iris, dan kerupuk udang.',
                'price' => 17000,
                'stock' => 30,
                'is_featured' => true,
            ],
            [
                'stand_id' => 7,
                'category_id' => $categories['mie']->id,
                'name' => 'Mie Dok-Dok Kuah Kental Bugfix',
                'description' => 'Mie rebus bumbu gurih pekat berkuah kental telur dengan aroma bawang dan cabai rawit penambah energi coding.',
                'price' => 13000,
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'stand_id' => 7,
                'category_id' => $categories['minuman']->id,
                'name' => 'Cold Brew Coffee Debug Mode',
                'description' => 'Kopi dingin seduh lambat 12 jam yang halus, rendah asam, dan berkafein tinggi untuk fokus belajar.',
                'price' => 12000,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'stand_id' => 7,
                'category_id' => $categories['snack']->id,
                'name' => 'Risol Mayo Beef Melted (2 pcs)',
                'description' => 'Risol renyah berselimut breadcrumbs emas dengan isian daging asap, telur rebus, dan mayo gurih lumer.',
                'price' => 9000,
                'stock' => 25,
                'is_featured' => true,
            ],
            [
                'stand_id' => 7,
                'category_id' => $categories['dessert']->id,
                'name' => 'Fudgy Brownies Dark Chocolate',
                'description' => 'Brownies panggang tekstur chewy dengan coklat pekat murni bertabur choco chips gurih.',
                'price' => 10000,
                'stock' => 15,
                'is_featured' => false,
            ],

            // Stand 8: Akuntansi 2
            [
                'stand_id' => 8,
                'category_id' => $categories['makanan']->id,
                'name' => 'Nasi Liwet Komplit Solo',
                'description' => 'Nasi gurih liwet santan dengan suwiran ayam opor lembut, sayur labu siam manis gurih, dan areh santan kental.',
                'price' => 18000,
                'stock' => 20,
                'is_featured' => false,
            ],
            [
                'stand_id' => 8,
                'category_id' => $categories['minuman']->id,
                'name' => 'Jus Alpukat Kocok Coklat Milo',
                'description' => 'Alpukat mentega matang pohon dikocok kental dengan lumuran susu kental manis coklat dan bubuk Milo.',
                'price' => 12000,
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'stand_id' => 8,
                'category_id' => $categories['snack']->id,
                'name' => 'Jasuke Creamy Keju Susu',
                'description' => 'Jagung manis pipil kukus hangat diaduk butter gurih, susu manis, dan parutan keju kraft melimpah.',
                'price' => 8000,
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'stand_id' => 8,
                'category_id' => $categories['dessert']->id,
                'name' => 'Es Pisang Ijo Makassar',
                'description' => 'Pisang raja berbalut adonan daun suji hijau lembut disajikan bersama bubur sumsum manis dan sirup merah DHT.',
                'price' => 12000,
                'stock' => 15,
                'is_featured' => false,
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $prod) {
            $createdProducts[] = Product::create($prod);
        }

        // 5. Contoh Transaksi Awal (Untuk Dashboard dan Laporan)
        // Order 1: Pesanan Dibuat
        $order1 = Order::create([
            'user_id' => $siswa1->id,
            'order_number' => 'ORD-' . date('Ymd') . '-0001',
            'customer_name' => $siswa1->name,
            'customer_nis' => $siswa1->nis,
            'customer_class' => $siswa1->class_room,
            'customer_room' => 'Ruang Lab RPL 2',
            'customer_phone' => $siswa1->phone,
            'total_price' => 29000,
            'payment_method' => 'qris',
            'status' => 'Pesanan Dibuat',
            'customer_note' => 'Sambal dipisah ya kak, terima kasih!',
        ]);

        OrderDetail::create([
            'order_id' => $order1->id,
            'product_id' => $createdProducts[0]->id, // Kopi Susu Aren (12000)
            'quantity' => 1,
            'price' => 12000,
            'subtotal' => 12000,
        ]);
        OrderDetail::create([
            'order_id' => $order1->id,
            'product_id' => $createdProducts[22]->id, // Nasi Goreng RPL (17000)
            'quantity' => 1,
            'price' => 17000,
            'subtotal' => 17000,
        ]);

        Payment::create([
            'order_id' => $order1->id,
            'payment_method' => 'qris',
            'amount' => 29000,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Order 2: Sedang Diproses
        $order2 = Order::create([
            'user_id' => $siswa2->id,
            'order_number' => 'ORD-' . date('Ymd') . '-0002',
            'customer_name' => $siswa2->name,
            'customer_nis' => $siswa2->nis,
            'customer_class' => $siswa2->class_room,
            'customer_room' => 'Ruang Kelas XI BD 2',
            'customer_phone' => $siswa2->phone,
            'total_price' => 27000,
            'payment_method' => 'cash',
            'status' => 'Sedang Diproses',
            'customer_note' => 'Diambil pas jam istirahat pertama.',
        ]);

        OrderDetail::create([
            'order_id' => $order2->id,
            'product_id' => $createdProducts[18]->id, // Dimsum Ayam Udang (15000)
            'quantity' => 1,
            'price' => 15000,
            'subtotal' => 15000,
        ]);
        OrderDetail::create([
            'order_id' => $order2->id,
            'product_id' => $createdProducts[14]->id, // Boba Brown Sugar (12000)
            'quantity' => 1,
            'price' => 12000,
            'subtotal' => 12000,
        ]);

        Payment::create([
            'order_id' => $order2->id,
            'payment_method' => 'cash',
            'amount' => 27000,
            'status' => 'pending',
        ]);

        // Order 3: Siap Diambil
        $order3 = Order::create([
            'user_id' => $siswa3->id,
            'order_number' => 'ORD-' . date('Ymd') . '-0003',
            'customer_name' => $siswa3->name,
            'customer_nis' => $siswa3->nis,
            'customer_class' => $siswa3->class_room,
            'customer_room' => 'Gazebo Sekolah',
            'customer_phone' => $siswa3->phone,
            'total_price' => 21000,
            'payment_method' => 'transfer',
            'status' => 'Siap Diambil',
            'customer_note' => 'Bawa sendok garpu ya kak.',
        ]);

        OrderDetail::create([
            'order_id' => $order3->id,
            'product_id' => $createdProducts[10]->id, // Ayam Geprek (15000)
            'quantity' => 1,
            'price' => 15000,
            'subtotal' => 15000,
        ]);
        OrderDetail::create([
            'order_id' => $order3->id,
            'product_id' => $createdProducts[6]->id, // Es Lemon Tea (6000)
            'quantity' => 1,
            'price' => 6000,
            'subtotal' => 6000,
        ]);

        Payment::create([
            'order_id' => $order3->id,
            'payment_method' => 'transfer',
            'amount' => 21000,
            'status' => 'paid',
            'paid_at' => now()->subMinutes(15),
        ]);

        // Order 4: Selesai
        $order4 = Order::create([
            'user_id' => $siswa1->id,
            'order_number' => 'ORD-' . date('Ymd', strtotime('-1 day')) . '-0004',
            'customer_name' => $siswa1->name,
            'customer_nis' => $siswa1->nis,
            'customer_class' => $siswa1->class_room,
            'customer_room' => 'Ruang Teori 1',
            'customer_phone' => $siswa1->phone,
            'total_price' => 22000,
            'payment_method' => 'qris',
            'status' => 'Selesai',
            'customer_note' => 'Sudah diambil kemarin.',
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);

        OrderDetail::create([
            'order_id' => $order4->id,
            'product_id' => $createdProducts[12]->id, // Bento Beef (22000)
            'quantity' => 1,
            'price' => 22000,
            'subtotal' => 22000,
            'created_at' => now()->subDay(),
        ]);

        Payment::create([
            'order_id' => $order4->id,
            'payment_method' => 'qris',
            'amount' => 22000,
            'status' => 'paid',
            'paid_at' => now()->subDay(),
            'created_at' => now()->subDay(),
        ]);
    }
}
