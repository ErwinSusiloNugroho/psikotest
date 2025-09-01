<?php
session_start();
require_once 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: form-identitas.php");
    exit();
}

// Ambil data peserta
$peserta_data = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM peserta WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $peserta_data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$peserta_data) {
        header("Location: form-identitas.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error mengambil data peserta: " . $e->getMessage());
}

// Ambil hasil tes
$hasil_tes = null;
try {
    $stmt = $pdo->prepare("SELECT * FROM hasil_tes WHERE peserta_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $hasil_tes = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$hasil_tes) {
        // Jika belum ada hasil, redirect ke soal
        header("Location: soal_psikotes.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error mengambil hasil tes: " . $e->getMessage());
}

// Ambil detail jawaban
$detail_jawaban = [];
try {
    $stmt = $pdo->prepare("
        SELECT jp.jawaban, sp.nomor_soal, sp.pertanyaan 
        FROM jawaban_peserta jp 
        JOIN soal_psikotes sp ON jp.soal_id = sp.id 
        WHERE jp.peserta_id = ? 
        ORDER BY sp.nomor_soal
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $detail_jawaban = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil detail jawaban: " . $e->getMessage());
}

// Hitung total soal
$total_soal = count($detail_jawaban);

// Fungsi untuk mendapatkan warna berdasarkan kategori
function getKategoriColor($kategori) {
    switch($kategori) {
        case 'Rendah':
            return 'green';
        case 'Sedang':
            return 'yellow';
        case 'Tinggi':
            return 'orange';
        case 'Sangat Tinggi':
            return 'red';
        default:
            return 'gray';
    }
}

// Fungsi untuk mendapatkan icon berdasarkan kategori
function getKategoriIcon($kategori) {
    switch($kategori) {
        case 'Rendah':
            return 'smile';
        case 'Sedang':
            return 'meh';
        case 'Tinggi':
            return 'frown';
        case 'Sangat Tinggi':
            return 'tired';
        default:
            return 'question';
    }
}

// Rekomendasi berdasarkan kategori
function getRekomendasi($kategori) {
    switch($kategori) {
        case 'Rendah':
            return [
                'Pertahankan keseimbangan hidup dan kerja yang sudah baik',
                'Lanjutkan aktivitas fisik dan hobi yang menyenangkan',
                'Tetap jaga komunikasi yang baik dengan rekan kerja',
                'Evaluasi berkala untuk mempertahankan kondisi positif'
            ];
        case 'Sedang':
            return [
                'Mulai identifikasi sumber stres utama dalam pekerjaan',
                'Terapkan teknik manajemen stres seperti meditasi atau yoga',
                'Atur jadwal istirahat yang teratur dan berkualitas',
                'Bicarakan dengan atasan tentang beban kerja yang sesuai',
                'Perbanyak aktivitas yang menyenangkan di luar kerja'
            ];
        case 'Tinggi':
            return [
                'Segera evaluasi dan kurangi beban kerja yang berlebihan',
                'Konsultasi dengan counselor atau psikolog profesional',
                'Ambil cuti atau istirahat yang cukup untuk recovery',
                'Perkuat sistem dukungan sosial (keluarga, teman)',
                'Pertimbangkan perubahan lingkungan kerja jika memungkinkan',
                'Fokus pada self-care dan kesehatan mental'
            ];
        case 'Sangat Tinggi':
            return [
                'SEGERA konsultasi dengan psikolog atau psikiater',
                'Pertimbangkan untuk mengambil medical leave',
                'Dapatkan dukungan profesional untuk manajemen stres',
                'Evaluasi ulang karir dan lingkungan kerja secara menyeluruh',
                'Fokus pada pemulihan kesehatan mental sebagai prioritas utama',
                'Libatkan keluarga dalam proses pemulihan',
                'Jangan ragu untuk mencari bantuan profesional'
            ];
        default:
            return ['Konsultasi dengan profesional untuk evaluasi lebih lanjut'];
    }
}

$color = getKategoriColor($hasil_tes['kategori']);
$icon = getKategoriIcon($hasil_tes['kategori']);
$rekomendasi = getRekomendasi($hasil_tes['kategori']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tes Burnout - Burnout_id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-brain text-3xl text-indigo-600 mr-3"></i>
                    <h1 class="text-2xl font-bold text-gray-800">Burnout_id</h1>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">
                        <i class="fas fa-check mr-1"></i>
                        Selesai
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Progress Bar -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500">Identitas</span>
                <span class="text-sm text-gray-500">Soal Psikotes</span>
                <span class="text-sm font-medium text-green-600">Hasil</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full w-full"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="bg-<?php echo $color; ?>-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-<?php echo $icon; ?> text-3xl text-<?php echo $color; ?>-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Hasil Tes Burnout</h2>
                <p class="text-gray-600">
                    Berikut adalah hasil analisis tingkat burnout Anda berdasarkan jawaban yang telah diberikan
                </p>
            </div>

            <!-- Peserta Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Informasi Peserta</h3>
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        <?php echo date('d M Y H:i', strtotime($hasil_tes['completed_at'])); ?>
                    </span>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-user text-indigo-600 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($peserta_data['nama']); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-venus-mars text-indigo-600 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-500">Jenis Kelamin</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($peserta_data['jenis_kelamin']); ?></p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-birthday-cake text-indigo-600 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-500">Usia</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($peserta_data['usia']); ?> tahun</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-briefcase text-indigo-600 mr-3"></i>
                        <div>
                            <p class="text-sm text-gray-500">Pekerjaan</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($peserta_data['pekerjaan']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 mb-8">
                <!-- Hasil Utama -->
                <div class="lg:col-span-2">
                    <!-- Skor dan Kategori -->
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Hasil Tes Burnout Anda</h3>
                        
                        <div class="text-center mb-8">
                            <div class="bg-<?php echo $color; ?>-100 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-4">
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-<?php echo $color; ?>-700">
                                        <?php echo number_format($hasil_tes['persentase'], 1); ?>%
                                    </div>
                                    <div class="text-sm text-<?php echo $color; ?>-600">Skor</div>
                                </div>
                            </div>
                            <h4 class="text-3xl font-bold text-<?php echo $color; ?>-700 mb-2">
                                <?php echo $hasil_tes['kategori']; ?>
                            </h4>
                            <p class="text-gray-600 text-lg max-w-md mx-auto">
                                <?php echo htmlspecialchars($hasil_tes['deskripsi_kategori']); ?>
                            </p>
                        </div>

                        <!-- Statistik Detail -->
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-gray-800"><?php echo $hasil_tes['total_jawaban_ya']; ?></div>
                                <div class="text-sm text-gray-600">Jawaban "Ya"</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-gray-800"><?php echo ($total_soal - $hasil_tes['total_jawaban_ya']); ?></div>
                                <div class="text-sm text-gray-600">Jawaban "Tidak"</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-gray-800"><?php echo $total_soal; ?></div>
                                <div class="text-sm text-gray-600">Total Soal</div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h4 class="text-xl font-semibold text-gray-800 mb-4">Visualisasi Hasil</h4>
                        <div class="flex justify-center">
                            <canvas id="burnoutChart" width="300" height="300"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Interpretasi -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h4 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                            Interpretasi
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                                <span>Rendah (0-25%): Normal</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded mr-3"></div>
                                <span>Sedang (26-50%): Perlu Perhatian</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-orange-500 rounded mr-3"></div>
                                <span>Tinggi (51-75%): Berbahaya</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded mr-3"></div>
                                <span>Sangat Tinggi (76-100%): Kritis</span>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Penting -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-amber-800 mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle text-amber-600 mr-2"></i>
                            Catatan Penting
                        </h4>
                        <p class="text-amber-700 text-sm leading-relaxed">
                            Hasil tes ini bersifat informatif dan tidak menggantikan diagnosis profesional. 
                            Jika Anda merasa mengalami masalah serius, segera konsultasi dengan psikolog atau dokter.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Rekomendasi -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-lightbulb text-yellow-500 mr-3"></i>
                    Rekomendasi untuk Anda
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <?php foreach ($rekomendasi as $index => $item): ?>
                    <div class="flex items-start">
                        <div class="bg-indigo-100 text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-4 mt-1 flex-shrink-0">
                            <?php echo $index + 1; ?>
                        </div>
                        <p class="text-gray-700 leading-relaxed"><?php echo htmlspecialchars($item); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Detail Jawaban -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Detail Jawaban Anda</h3>
                    <button id="toggleDetail" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                    </button>
                </div>
                <div id="detailJawaban" class="hidden">
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        <?php foreach ($detail_jawaban as $jawaban): ?>
                        <div class="flex items-start border-b border-gray-100 pb-3">
                            <div class="bg-gray-100 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-4 mt-1 flex-shrink-0">
                                <?php echo $jawaban['nomor_soal']; ?>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800 mb-2"><?php echo htmlspecialchars($jawaban['pertanyaan']); ?></p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo $jawaban['jawaban'] === 'YA' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'; ?>">
                                    <i class="fas fa-<?php echo $jawaban['jawaban'] === 'YA' ? 'check' : 'times'; ?> mr-1"></i>
                                    <?php echo $jawaban['jawaban']; ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg flex items-center justify-center">
                    <i class="fas fa-print mr-2"></i>
                    Cetak Hasil
                </button>
                <a href="soal_psikotes.php" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg flex items-center justify-center">
                    <i class="fas fa-redo mr-2"></i>
                    Ulangi Tes
                </a>
                <button onclick="resetSession()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg flex items-center justify-center">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Keluar
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-6 text-center">
            <p class="mb-2">&copy; 2024 Psikotest Burnout. Semua hak dilindungi.</p>
            <p class="text-gray-400 text-sm">
                Hasil tes ini bersifat informatif dan tidak menggantikan konsultasi profesional
            </p>
        </div>
    </footer>

    <script>
        // Chart.js untuk visualisasi
        const ctx = document.getElementById('burnoutChart').getContext('2d');
        const persentase = <?php echo $hasil_tes['persentase']; ?>;
        const kategori = '<?php echo $hasil_tes['kategori']; ?>';
        
        // Warna berdasarkan kategori
        const colors = {
            'Rendah': '#10B981',
            'Sedang': '#F59E0B', 
            'Tinggi': '#F97316',
            'Sangat Tinggi': '#EF4444'
        };
        
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Burnout Score', 'Normal'],
                datasets: [{
                    data: [persentase, 100 - persentase],
                    backgroundColor: [
                        colors[kategori] || '#6B7280',
                        '#E5E7EB'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });

        // Toggle detail jawaban
        document.getElementById('toggleDetail').addEventListener('click', function() {
            const detail = document.getElementById('detailJawaban');
            const button = this;
            
            if (detail.classList.contains('hidden')) {
                detail.classList.remove('hidden');
                button.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Sembunyikan Detail';
            } else {
                detail.classList.add('hidden');
                button.innerHTML = '<i class="fas fa-eye mr-2"></i>Lihat Detail';
            }
        });

        // Reset session
        function resetSession() {
            if (confirm('Apakah Anda yakin ingin keluar? Data sesi akan dihapus.')) {
                fetch('logout.php', {
                    method: 'POST'
                }).then(() => {
                    window.location.href = 'index.html';
                });
            }
        }

        // Print styles
        const printStyles = `
            <style>
                @media print {
                    body { -webkit-print-color-adjust: exact; }
                    header, footer, .no-print { display: none !important; }
                    .container { max-width: none !important; margin: 0 !important; padding: 0 !important; }
                }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', printStyles);
    </script>
</body>
</html>