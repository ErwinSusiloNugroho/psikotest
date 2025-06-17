<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psikotest Burnout - Prodi BK Unipma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Header with Admin Login -->
    <header class="bg-white shadow-lg relative">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center animate__animated animate__fadeInDown">
                    <i class="fas fa-brain text-3xl text-indigo-600 mr-3"></i>
                    <a href="index.php" class="text-2xl font-bold text-gray-800 hover:text-indigo-700 transition-colors duration-200">
                    Psikotest Burnout
                </a>
                </div>
                <!-- Animate.css CDN -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
                
                <!-- Admin Login Button -->
                <a href="admin-login.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-user-shield mr-2"></i>
                    Login Admin
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16 animate__animated animate__fadeInDown">
            <h2 class="text-5xl font-bold text-gray-800 mb-6">Selamat Datang</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-8">
            Layanan Psikotest Burnout untuk membantu Anda memahami tingkat kelelahan fisik, emosional, dan mental yang sedang dialami.
            </p>
        </div>

        <!-- Team Identity Card -->
        <div class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden mb-16">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-8">
                <h3 class="text-3xl font-bold text-white text-center flex items-center justify-center">
                    <i class="fas fa-users mr-4"></i>
                    Identitas Kelompok
                </h3>
            </div>
            
            <div class="px-8 py-12">
                <!-- University Info -->
                <div class="text-center mb-12">
                    <h4 class="text-2xl font-bold text-gray-800 mb-2">Program Studi Bimbingan dan Konseling</h4>
                    <h5 class="text-xl text-indigo-600 font-semibold mb-4">Universitas PGRI Madiun</h5>
                    <div class="w-24 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 mx-auto rounded-full"></div>
                </div>
                
                <!-- Group Photo Section -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-100 rounded-3xl p-8 shadow-lg mb-12">
                    <!-- Large Group Photo Container -->
                    <div class="text-center mb-8">
                        <div class="relative mx-auto mb-6 group">
                            <!-- Placeholder for group photo - replace with actual image -->
                            <div class="w-full max-w-4xl aspect-[16/7] bg-gray-300 rounded-2xl mx-auto flex items-center justify-center overflow-hidden shadow-xl transition-transform duration-500 group-hover:scale-105 group-hover:rotate-1 group-hover:shadow-2xl">
                                <img src="img/grub.jpeg" alt="Foto Bersama Kelompok" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110 group-hover:rotate-1">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Members Section -->
                <div class="mb-12">
                    <h5 class="text-2xl font-bold text-gray-800 mb-8 text-center">Anggota Kelompok</h5>
                    
                    <!-- Team Members Grid -->
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Member 1 -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/1.jpeg" alt="Anggota 1" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Via Noervaiza</h6>
                            <p class="text-indigo-600 font-medium mb-2">(2320103037)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        
                        <!-- Member 2 -->
                        <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/2.jpeg" alt="Anggota 2" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Nur Rohmah K.W</h6>
                            <p class="text-purple-600 font-medium mb-2">(2302103041)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        
                        <!-- Member 3 -->
                        <div class="bg-gradient-to-br from-green-50 to-teal-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/3.jpeg " alt="Anggota 3" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Bunga Kurnia P</h6>
                            <p class="text-teal-600 font-medium mb-2">(2302103045)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        
                        <!-- Member 4 -->
                        <div class="bg-gradient-to-br from-orange-50 to-red-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/4.jpeg" alt="Anggota 4" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Revina Eka A.P</h6>
                            <p class="text-orange-600 font-medium mb-2">(2302103005)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        
                        <!-- Member 5 -->
                        <div class="bg-gradient-to-br from-yellow-50 to-orange-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/5.jpeg" alt="Anggota 5" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Aynun Putria D.S</h6>
                            <p class="text-yellow-600 font-medium mb-2">(2302103015)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>

                        <!-- Member 6 -->
                        <div class="bg-gradient-to-br from-pink-50 to-red-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                               <img src="img/6.jpeg" alt="Anggota 6" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Birgitta Ratna O</h6>
                            <p class="text-pink-600 font-medium mb-2">(2302103020)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                           
                        <!-- member 7 -->
                        <div class="bg-gradient-to-br from-pink-50 to-red-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/7.jpeg" alt="Anggota 7" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Eka Melinda</h6>
                            <p class="text-pink-600 font-medium mb-2">(2302103024)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>

                        <div class="bg-gradient-to-br from-pink-50 to-red-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/8.jpeg" alt="Anggota 8" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Egidia Parwina A</h6>
                            <p class="text-red-600 font-medium mb-2">(2302103036)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        <div class="bg-gradient-to-br from-pink-50 to-red-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/9.jpeg" alt="Anggota 9" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Allin Rosmawandini </h6>
                            <p class="text-pink-600 font-medium mb-2">(2302103007)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/10.jpeg" alt="Anggota 10" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Rifqi Maulana F</h6>
                            <p class="text-indigo-600 font-medium mb-2">(2302103026)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        <div class="bg-gradient-to-br from-pink-50 to-red-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/11.jpeg" alt="Anggota 11" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Arkhim Nur Ikhwanan K </h6>
                            <p class="text-green-600 font-medium mb-2">(2302103001)</p>
                            <p class="text-gray-600 text-sm">Prodi BK</p>
                        </div>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden">
                                <img src="img/12.jpeg" alt="Anggota 12" class="w-full h-full object-cover">
                            </div>
                            <h6 class="font-bold text-gray-800 text-lg mb-2">Rona Sabrina</h6>
                            <p class="text-indigo-600 font-medium mb-2">(2402103069)</p>
                            <p class="text-gray-600 text-sm">Podi RPL</p>
                        </div>
                    </div>
                </div>
                
                <!-- Project Description -->
                <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8">
                    <h5 class="text-xl font-bold text-gray-800 mb-4 text-center">Tentang Proyek Ini</h5>
                    <p class="text-gray-700 text-center leading-relaxed">
                        Proyek Psikotest Burnout ini dikembangkan sebagai bagian dari tugas pada Program Studi Bimbingan dan Konseling Universitas PGRI Madiun. Aplikasi ini bertujuan untuk membantu individu mengenali tingkat burnout yang sedang dialami.
                    </p>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <div class="mb-8">
                <h3 class="text-3xl font-bold text-gray-800 mb-4">Siap Memulai Tes?</h3>
                <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                    Kenali kondisi mental Anda melalui tes burnout yang telah kami rancang. Tes ini akan membantu Anda memahami tingkat kelelahan yang sedang dialami.
                </p>
            </div>
            
            <!-- Main Test Button -->
            <a href="Udasboard.html" 
               class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-6 px-12 rounded-full text-xl shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300 mb-6">
                <i class="fas fa-clipboard-check mr-4"></i>
                Mulai Tes Sekarang
            </a>
            
            <div class="flex items-center justify-center text-gray-500 text-sm">
                <i class="fas fa-clock mr-2"></i>
                <span>Estimasi waktu: 10-15 menit</span>
                <span class="mx-3">•</span>
                <i class="fas fa-users mr-2"></i>
                <span>15 pertanyaan</span>
                <span class="mx-3">•</span>
                <i class="fas fa-shield-alt mr-2"></i>
                <span>Privasi Anda terjaga</span>
            </div>
        </div>

        <!-- Features -->
        <div class="mt-20 grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-user-check text-2xl text-indigo-600"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Mudah Digunakan</h4>
                <p class="text-gray-600 text-sm">Tampilan sederhana dan mudah dipahami oleh semua kalangan.</p>
            </div>
            
            <div class="text-center">
                <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Hasil Akurat</h4>
                <p class="text-gray-600 text-sm">Berdasarkan standar psikologi yang telah teruji dan valid.</p>
            </div>
            
            <div class="text-center">
                <div class="bg-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-lightbulb text-2xl text-yellow-600"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Saran Praktis</h4>
                <p class="text-gray-600 text-sm">Dapatkan rekomendasi dan tips untuk mengatasi burnout.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-20">
        <div class="container mx-auto px-6 text-center">
            <div class="mb-4">
                <i class="fas fa-brain text-2xl text-indigo-400 mb-2"></i>
                <p class="font-semibold text-lg">Psikotest Burnout</p>
            </div>
            <p class="mb-2">&copy; 2025 Program Studi Bimbingan dan Konseling - Universitas PGRI Madiun</p>
            <p class="text-gray-400 text-sm mb-4">
                Tes ini hanya untuk skrining awal. Untuk diagnosis yang akurat, konsultasikan dengan profesional kesehatan mental.
            </p>
        </div>
    </footer>
</body>
</html>