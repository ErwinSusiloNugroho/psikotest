<?php
session_start();
require_once 'config.php';
$errors = [];
$success = false;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Ambil semua soal dari database
try {
    $stmt = $pdo->query("SELECT * FROM soal_psikotes ORDER BY nomor_soal");
    $soal_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil soal: " . $e->getMessage());
}

$errors = [];

// Proses form submission
if ($_POST) {
    // Validasi jawaban
    $total_soal = count($soal_list);
    $jawaban_count = 0;
    
    foreach ($soal_list as $soal) {
        if (isset($_POST['soal_' . $soal['id']])) {
            $jawaban_count++;
        }
    }
    
    if ($jawaban_count < $total_soal) {
        $errors[] = "Mohon jawab semua pertanyaan sebelum melanjutkan.";
    }
    
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Ambil peserta_id dari session atau insert peserta baru
            $peserta_data = $_SESSION['peserta_data'];
            
            // Insert data peserta jika belum ada
            if (!isset($_SESSION['peserta_id'])) {
                $stmt = $pdo->prepare("INSERT INTO peserta (nama, jenis_kelamin, usia, pekerjaan, pendidikan) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $peserta_data['nama'],
                    $peserta_data['jenis_kelamin'],
                    $peserta_data['usia'],
                    $peserta_data['pekerjaan'],
                    $peserta_data['pendidikan']
                ]);
                
                $peserta_id = $pdo->lastInsertId();
                $_SESSION['peserta_id'] = $peserta_id;
            } else {
                $peserta_id = $_SESSION['peserta_id'];
            }
            
            // Hapus jawaban lama jika ada (untuk kasus re-submit)
            $stmt = $pdo->prepare("DELETE FROM jawaban_peserta WHERE peserta_id = ?");
            $stmt->execute([$peserta_id]);
            
            // Insert jawaban baru
            $total_ya = 0;
            $stmt = $pdo->prepare("INSERT INTO jawaban_peserta (peserta_id, soal_id, jawaban) VALUES (?, ?, ?)");
            
            foreach ($soal_list as $soal) {
                $jawaban = $_POST['soal_' . $soal['id']];
                $stmt->execute([$peserta_id, $soal['id'], $jawaban]);
                
                if ($jawaban === 'YA') {
                    $total_ya++;
                }
            }
            
            // Hitung skor dan kategori
            $total_skor = $total_ya; // Setiap jawaban YA = 1 poin
            $persentase = ($total_ya / $total_soal) * 100;
            
            // Tentukan kategori berdasarkan persentase
            if ($persentase <= 25) {
                $kategori = 'Rendah';
                $deskripsi = 'Tingkat burnout Anda masih dalam batas normal. Terus jaga keseimbangan hidup dan kerja.';
            } elseif ($persentase <= 50) {
                $kategori = 'Sedang';
                $deskripsi = 'Anda mulai mengalami gejala burnout. Perlu perhatian dan perbaikan dalam manajemen stres.';
            } elseif ($persentase <= 75) {
                $kategori = 'Tinggi';
                $deskripsi = 'Tingkat burnout Anda cukup tinggi. Sangat disarankan untuk mencari bantuan dan melakukan perubahan.';
            } else {
                $kategori = 'Sangat Tinggi';
                $deskripsi = 'Tingkat burnout Anda sangat tinggi. Segera konsultasi dengan profesional dan ambil tindakan nyata.';
            }
            
            // Hapus hasil lama jika ada
            $stmt = $pdo->prepare("DELETE FROM hasil_tes WHERE peserta_id = ?");
            $stmt->execute([$peserta_id]);
            
            // Insert hasil tes
            $stmt = $pdo->prepare("INSERT INTO hasil_tes (peserta_id, total_jawaban_ya, total_skor, persentase, kategori, deskripsi_kategori) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$peserta_id, $total_ya, $total_skor, $persentase, $kategori, $deskripsi]);
            
            $pdo->commit();
            
            // Redirect ke halaman hasil
            header('Location: hasil.php');
            exit;
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = "Terjadi kesalahan saat menyimpan data: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soal Psikotes - Burnout_id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full font-medium">
                        Langkah 2 dari 3
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
                <span class="text-sm font-medium text-indigo-600">Soal Psikotes</span>
                <span class="text-sm text-gray-500">Hasil</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full w-2/3"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clipboard-check text-2xl text-indigo-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Tes Burnout</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Jawablah setiap pertanyaan dengan jujur sesuai dengan kondisi yang Anda alami dalam 2-3 minggu terakhir. 
                    Tidak ada jawaban yang benar atau salah.
                </p>
            </div>

            <!-- Peserta Info -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-2xl text-indigo-600 mr-3"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['peserta_data']['nama']); ?></h3>
                            <p class="text-sm text-gray-600">
                                <?php echo htmlspecialchars($_SESSION['peserta_data']['jenis_kelamin']); ?> • 
                                <?php echo htmlspecialchars($_SESSION['peserta_data']['usia']); ?> tahun • 
                                <?php echo htmlspecialchars($_SESSION['peserta_data']['pekerjaan']); ?>
                            </p>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500"><?php echo count($soal_list); ?> pertanyaan</span>
                </div>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-red-800 mb-2">Terjadi Kesalahan:</h4>
                        <ul class="text-red-700 text-sm space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li>• <?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Instructions -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-8">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-amber-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-amber-800 mb-2">Petunjuk Pengisian:</h4>
                        <div class="text-amber-700 text-sm space-y-1">
                            <p>• Pilih <strong>"YA"</strong> jika pernyataan sesuai dengan kondisi Anda</p>
                            <p>• Pilih <strong>"TIDAK"</strong> jika pernyataan tidak sesuai dengan kondisi Anda</p>
                            <p>• Jawablah berdasarkan pengalaman 2-3 minggu terakhir</p>
                            <p>• Semua pertanyaan wajib dijawab</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Soal -->
            <form method="POST" id="soalForm">
                <div class="space-y-6">
                    <?php foreach ($soal_list as $index => $soal): ?>
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                            <h3 class="text-white font-semibold flex items-center">
                                <span class="bg-white text-indigo-600 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3">
                                    <?php echo $soal['nomor_soal']; ?>
                                </span>
                                Pertanyaan <?php echo $soal['nomor_soal']; ?>
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <p class="text-gray-800 text-lg mb-6 leading-relaxed">
                                <?php echo htmlspecialchars($soal['pertanyaan']); ?>
                            </p>
                            
                            <div class="flex space-x-4">
                                <label class="flex-1 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="soal_<?php echo $soal['id']; ?>" 
                                        value="YA" 
                                        class="sr-only peer"
                                        <?php echo (isset($_POST['soal_' . $soal['id']]) && $_POST['soal_' . $soal['id']] === 'YA') ? 'checked' : ''; ?>
                                    >
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-100 transition-all duration-200">
                                        <i class="fas fa-check-circle text-2xl mb-2 peer-checked:text-green-500"></i>
                                        <div class="font-semibold">YA</div>
                                        <div class="text-sm text-gray-500">Sesuai kondisi saya</div>
                                    </div>
                                </label>
                                
                                <label class="flex-1 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="soal_<?php echo $soal['id']; ?>" 
                                        value="TIDAK" 
                                        class="sr-only peer"
                                        <?php echo (isset($_POST['soal_' . $soal['id']]) && $_POST['soal_' . $soal['id']] === 'TIDAK') ? 'checked' : ''; ?>
                                    >
                                    <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 text-center peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-gray-100 transition-all duration-200">
                                        <i class="fas fa-times-circle text-2xl mb-2 peer-checked:text-red-500"></i>
                                        <div class="font-semibold">TIDAK</div>
                                        <div class="text-sm text-gray-500">Tidak sesuai kondisi saya</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Progress Info -->
                <div class="bg-white rounded-lg shadow-md p-4 mt-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line text-indigo-600 mr-3"></i>
                            <span class="text-gray-600">Progress pengisian:</span>
                        </div>
                        <div class="flex items-center">
                            <span id="progressText" class="text-sm text-gray-600 mr-3">0 dari <?php echo count($soal_list); ?> terjawab</span>
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div id="progressBar" class="bg-indigo-600 h-2 rounded-full w-0 transition-all duration-300"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center mt-8">
                    <button 
                        type="submit"
                        id="submitBtn"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-lg text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        disabled
                    >
                        <i class="fas fa-calculator mr-3"></i>
                        Hitung Hasil Tes Burnout
                    </button>
                </div>
            </form>

            <!-- Navigation -->
            <div class="flex justify-between items-center mt-8">
                <a 
                    href="identitas.php"
                    class="flex items-center text-gray-600 hover:text-indigo-600 transition-colors"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Identitas
                </a>
                
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Estimasi waktu: 5-10 menit
                    </p>
                </div>
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
        // Progress tracking
        function updateProgress() {
            const totalSoal = <?php echo count($soal_list); ?>;
            const answeredInputs = document.querySelectorAll('input[type="radio"]:checked');
            const answered = answeredInputs.length;
            const percentage = (answered / totalSoal) * 100;
            
            document.getElementById('progressText').textContent = `${answered} dari ${totalSoal} terjawab`;
            document.getElementById('progressBar').style.width = `${percentage}%`;
            
            const submitBtn = document.getElementById('submitBtn');
            if (answered === totalSoal) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-calculator mr-3"></i>Hitung Hasil Tes Burnout';
            } else {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-calculator mr-3"></i>Jawab Semua Pertanyaan Dulu';
            }
        }

        // Add event listeners to all radio buttons
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[type="radio"]');
            radioButtons.forEach(radio => {
                radio.addEventListener('change', updateProgress);
            });
            
            // Initial progress check
            updateProgress();
        });

        // Form validation before submit
        document.getElementById('soalForm').addEventListener('submit', function(e) {
            const totalSoal = <?php echo count($soal_list); ?>;
            const answeredInputs = document.querySelectorAll('input[type="radio"]:checked');
            
            if (answeredInputs.length < totalSoal) {
                e.preventDefault();
                alert('Mohon jawab semua pertanyaan sebelum melanjutkan.');
                return false;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Sedang Memproses...';
        });
    </script>
</body>
</html>