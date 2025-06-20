<?php
session_start();
require_once 'config.php';

// Proses form jika ada data POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validasi input
        $nama = trim($_POST['nama']);
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $usia = (int)$_POST['usia'];
        $pekerjaan = trim($_POST['pekerjaan']);
        $pendidikan = $_POST['pendidikan'];
        $agreement = isset($_POST['agreement']) ? 1 : 0;
        
        // Validasi data
        $errors = [];
        
        if (empty($nama)) {
            $errors[] = "Nama lengkap harus diisi";
        }
        
        if (empty($jenis_kelamin)) {
            $errors[] = "Jenis kelamin harus dipilih";
        }
        
        if ($usia < 15 || $usia > 70) {
            $errors[] = "Usia harus antara 15-70 tahun";
        }
        
        if (empty($pekerjaan)) {
            $errors[] = "Pekerjaan harus diisi";
        }
        
        if (empty($pendidikan)) {
            $errors[] = "Pendidikan terakhir harus dipilih";
        }
        
        if (!$agreement) {
            $errors[] = "Anda harus menyetujui persyaratan";
        }
        
        // Jika tidak ada error, simpan ke database
        if (empty($errors)) {
            // Insert data ke tabel peserta (tidak perlu CREATE TABLE lagi)
            $sql = "INSERT INTO peserta (nama, jenis_kelamin, usia, pekerjaan, pendidikan) 
                    VALUES (:nama, :jenis_kelamin, :usia, :pekerjaan, :pendidikan)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':jenis_kelamin', $jenis_kelamin);
            $stmt->bindParam(':usia', $usia);
            $stmt->bindParam(':pekerjaan', $pekerjaan);
            $stmt->bindParam(':pendidikan', $pendidikan);
            
            if ($stmt->execute()) {
                // Simpan ID user ke session untuk langkah selanjutnya
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['user_nama'] = $nama;
                
                // Redirect ke halaman soal psikotes
                header("Location: soal_psikotes.php");
                exit();
            } else {
                $errors[] = "Gagal menyimpan data. Silakan coba lagi.";
            }
        }
        
    } catch (PDOException $e) {
        $errors[] = "Error database: " . $e->getMessage();
    } catch (Exception $e) {
        $errors[] = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas - Psikotest Burnout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-brain text-3xl text-indigo-600 mr-3"></i>
                    <h1 class="text-2xl font-bold text-gray-800">Psikotest Burnout</h1>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full font-medium">
                        Langkah 1 dari 3
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Progress Bar -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-indigo-600">Identitas</span>
                <span class="text-sm text-gray-500">Soal Psikotes</span>
                <span class="text-sm text-gray-500">Hasil</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full w-1/3"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-12">
        <div class="max-w-2xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-edit text-2xl text-indigo-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Data Identitas</h2>
                <p class="text-gray-600">
                    Mohon isi data diri Anda dengan lengkap dan benar untuk hasil yang akurat
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-clipboard-list mr-3"></i>
                        Formulir Identitas
                    </h3>
                </div>

                <form id="identitasForm" class="px-8 py-8 space-y-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-indigo-600"></i>
                            Nama Lengkap *
                        </label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Masukkan nama lengkap Anda"
                        >
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-venus-mars mr-2 text-indigo-600"></i>
                            Jenis Kelamin *
                        </label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="Laki-laki" required class="sr-only">
                                <div class="radio-custom mr-3"></div>
                                <span class="text-gray-700">Laki-laki</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="Perempuan" required class="sr-only">
                                <div class="radio-custom mr-3"></div>
                                <span class="text-gray-700">Perempuan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Usia -->
                    <div>
                        <label for="usia" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-birthday-cake mr-2 text-indigo-600"></i>
                            Usia *
                        </label>
                        <input 
                            type="number" 
                            id="usia" 
                            name="usia" 
                            required
                            min="15" 
                            max="70"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Masukkan usia Anda"
                        >
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label for="pekerjaan" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-2 text-indigo-600"></i>
                            Pekerjaan/Profesi *
                        </label>
                        <input 
                            type="text" 
                            id="pekerjaan" 
                            name="pekerjaan" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Contoh: Guru, Dokter, Karyawan Swasta, Mahasiswa"
                        >
                    </div>

                    <!-- Pendidikan -->
                    <div>
                        <label for="pendidikan" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-graduation-cap mr-2 text-indigo-600"></i>
                            Pendidikan Terakhir *
                        </label>
                        <select 
                            id="pendidikan" 
                            name="pendidikan" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                        >
                            <option value="">Pilih pendidikan terakhir</option>
                            <option value="SD">SD/Sederajat</option>
                            <option value="SMP">SMP/Sederajat</option>
                            <option value="SMA">SMA/SMK/Sederajat</option>
                            <option value="D3">Diploma 3 (D3)</option>
                            <option value="S1">Sarjana (S1)</option>
                            <option value="S2">Magister (S2)</option>
                            <option value="S3">Doktor (S3)</option>
                        </select>
                    </div>

                    <!-- Disclaimer -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800 mb-1">Kerahasiaan Data</h4>
                                <p class="text-blue-700 text-sm">
                                    Data yang Anda berikan akan dijaga kerahasiaannya dan hanya digunakan 
                                    untuk keperluan analisis hasil tes ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Agreement Checkbox -->
                    <div class="flex items-start">
                        <input 
                            type="checkbox" 
                            id="agreement" 
                            name="agreement" 
                            required
                            class="mt-1 mr-3 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        >
                        <label for="agreement" class="text-sm text-gray-700">
                            Saya menyetujui bahwa data yang saya berikan adalah benar dan akan menjawab 
                            semua pertanyaan dengan jujur sesuai kondisi yang saya alami. *
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-lg text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center"
                        >
                            <i class="fas fa-arrow-right mr-3"></i>
                            Yuk Kroscek Tingkat Burnout Kamu!
                        </button>
                    </div>
                </form>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center mt-8">
                <button 
                    onclick="history.back()"
                    class="flex items-center text-gray-600 hover:text-indigo-600 transition-colors"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </button>
                
                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Data Anda aman dan terlindungi
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
    src="script.js"
  </script>
</body>
</html>