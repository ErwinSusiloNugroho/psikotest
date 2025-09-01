<?php
session_start();

// Include database configuration
require_once 'config.php';

// Fungsi untuk log aktivitas admin
function logAdminActivity($admin_id, $action, $details = null) {
    global $pdo;
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$admin_id, $action, $details, $ip_address, $user_agent]);
}

// Fungsi untuk menghapus semua remember me tokens dan cookie
function clearAllRememberMe($admin_id = null) {
    global $pdo;
    
    // Hapus cookie remember me dengan berbagai cara
    if (isset($_COOKIE['admin_remember'])) {
        $token = $_COOKIE['admin_remember'];
        
        // Hapus cookie dengan berbagai path dan domain
        setcookie('admin_remember', '', time() - 3600, '/');
        setcookie('admin_remember', '', time() - 3600, '/', $_SERVER['HTTP_HOST']);
        setcookie('admin_remember', '', time() - 3600, '/', '.' . $_SERVER['HTTP_HOST']);
        
        // Hapus token dari database
        $stmt = $pdo->prepare("DELETE FROM admin_remember_tokens WHERE token = ?");
        $stmt->execute([$token]);
    }
    
    // Hapus semua token untuk admin tertentu
    if ($admin_id) {
        $stmt = $pdo->prepare("DELETE FROM admin_remember_tokens WHERE admin_id = ?");
        $stmt->execute([$admin_id]);
    }
    
    // Hapus semua token yang expired
    $stmt = $pdo->prepare("DELETE FROM admin_remember_tokens WHERE expires_at < NOW()");
    $stmt->execute();
}

// PERBAIKAN: Pastikan tidak ada auto login dari remember me token
// Hapus semua remember me tokens yang ada
clearAllRememberMe();

// PERBAIKAN: Jika ada session admin yang aktif, hapus semua
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    
    // Log aktivitas auto logout
    logAdminActivity($admin_id, 'AUTO_LOGOUT', 'Admin auto logout - menghapus semua remember me tokens');
    
    // Hapus semua remember me tokens untuk admin ini
    clearAllRememberMe($admin_id);
    
    // Hapus session
    session_destroy();
    session_start(); // Start fresh session
}

// Proses Login
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = sanitize_input($_POST['password']);
    
    if (!empty($username) && !empty($password)) {
        try {
            // Cari admin berdasarkan username
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            
            if ($admin && $admin['password'] === $password) {
                
                clearAllRememberMe($admin['id']);
                
                
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['login_time'] = date('Y-m-d H:i:s');
                $_SESSION['no_remember'] = true; // Flag untuk tidak menggunakan remember me
                
                // Log aktivitas login
                logAdminActivity($admin['id'], 'LOGIN', 'Admin berhasil login tanpa remember me dari IP: ' . $_SERVER['REMOTE_ADDR']);
                
                $success_message = 'Login berhasil! Mengalihkan ke dashboard...';
                
                // Redirect setelah 2 detik
                header("refresh:2;url=admin-dashboard.php");
            } else {
                $error_message = 'Username atau password salah!';
                
                // Log percobaan login gagal
                if ($admin) {
                    logAdminActivity($admin['id'], 'LOGIN_FAILED', 'Percobaan login dengan password salah dari IP: ' . $_SERVER['REMOTE_ADDR']);
                }
            }
        } catch (Exception $e) {
            $error_message = 'Terjadi kesalahan sistem. Silakan coba lagi.';
            error_log("Login error: " . $e->getMessage());
        }
    } else {
        $error_message = 'Username dan password harus diisi!';
    }
}

// PERBAIKAN: Jika sudah ada session admin, hapus dan paksa login ulang
if (isset($_SESSION['admin_id']) && !isset($success_message)) {
    $admin_id = $_SESSION['admin_id'];
    
    // Log aktivitas forced logout
    logAdminActivity($admin_id, 'FORCED_LOGOUT', 'Admin dipaksa logout untuk login ulang dari IP: ' . $_SERVER['REMOTE_ADDR']);
    
    // Hapus semua remember me tokens
    clearAllRememberMe($admin_id);
    
    // Hapus session
    session_destroy();
    session_start();
    
    // Set pesan
    $error_message = 'Sesi telah berakhir. Silakan login kembali.';
}

// Pesan logout sukses
$logout_message = '';
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logout_message = 'Anda telah berhasil logout. Silakan login kembali dengan username dan password.';
    
    // PERBAIKAN: Hapus semua cookie remember me di sisi client dengan JavaScript
    echo "<script>
        // Hapus cookie remember me dengan berbagai cara
        document.cookie = 'admin_remember=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'admin_remember=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname + ';';
        document.cookie = 'admin_remember=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + window.location.hostname + ';';
        
        // Hapus localStorage jika ada
        if (typeof Storage !== 'undefined') {
            localStorage.removeItem('admin_remember');
            localStorage.removeItem('admin_token');
            localStorage.removeItem('admin_session');
        }
        
        // Hapus sessionStorage jika ada
        if (typeof Storage !== 'undefined') {
            sessionStorage.clear();
        }
    </script>";
}

// PERBAIKAN: Cek parameter no_remember
if (isset($_GET['no_remember'])) {
    // Hapus semua remember me tokens yang mungkin tersisa
    clearAllRememberMe();
    
    echo "<script>
        // Hapus semua cookie dan storage
        document.cookie = 'admin_remember=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        if (typeof Storage !== 'undefined') {
            localStorage.clear();
            sessionStorage.clear();
        }
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Psikotest Burnout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Background Pattern -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-r from-indigo-300 to-purple-300 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-r from-blue-300 to-indigo-300 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-purple-200 to-pink-200 rounded-full opacity-10 animate-bounce"></div>
    </div>

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-sm shadow-lg relative z-10">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center animate__animated animate__fadeInDown">
                    <i class="fas fa-brain text-3xl text-indigo-600 mr-3"></i>
                    <a href="index.html" class="text-2xl font-bold text-gray-800 hover:text-indigo-700 transition-colors duration-200">
                        Burnout_id
                    </a>
                </div>
                
                <a href="index.html" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-300 flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 flex items-center justify-center min-h-screen px-6 py-12">
        <div class="w-full max-w-md">
            <!-- Login Card -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden animate__animated animate__fadeInUp">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-8 text-center">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-3xl text-white"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Admin Login</h2>
                    <p class="text-indigo-100">Masuk ke panel administrator</p>
                </div>

                <!-- Login Form -->
                <div class="px-8 py-8">
                    <form method="POST" class="space-y-6">
                        <!-- Username Field -->
                        <div class="space-y-2">
                            <label for="username" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-user mr-2 text-indigo-600"></i>
                                Username
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    required
                                    value=""
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-indigo-300"
                                    placeholder="Masukkan username"
                                    autocomplete="off"
                                >
                                <i class="fas fa-user absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                <i class="fas fa-lock mr-2 text-indigo-600"></i>
                                Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required
                                    value=""
                                    class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-indigo-300"
                                    placeholder="Masukkan password"
                                    autocomplete="off"
                                >
                                <i class="fas fa-lock absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <button 
                                    type="button" 
                                    id="togglePassword"
                                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                >
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Logout Message -->
                        <?php if (!empty($logout_message)): ?>
                        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span><?php echo htmlspecialchars($logout_message); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Error Message -->
                        <?php if (!empty($error_message)): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl animate__animated animate__shakeX">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span><?php echo htmlspecialchars($error_message); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Success Message -->
                        <?php if (!empty($success_message)): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span><?php echo htmlspecialchars($success_message); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Login Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center"
                        >
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Login Manual
                        </button>
                    </form>

                    <!-- Additional Info -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-shield-alt mr-2 text-indigo-600"></i>
                                Area terbatas untuk administrator
                            </p>
                            <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-lock mr-1"></i>
                                    Login Manual
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    Sesi 8 Jam
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-history mr-1"></i>
                                    Log Aktivitas
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-xs text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <strong>Keamanan:</strong> Setiap kali logout, Anda harus memasukkan username dan password kembali
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 bg-gray-800/90 backdrop-blur-sm text-white py-6">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center mb-2">
                <i class="fas fa-brain text-indigo-400 mr-2"></i>
                <span class="font-semibold">Psikotest Burnout Admin</span>
            </div>
            <p class="text-gray-400 text-sm">
                &copy; 2025 Program Studi Bimbingan dan Konseling - Universitas PGRI Madiun
            </p>
        </div>
    </footer>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // PERBAIKAN: Hapus autocomplete dan clear form saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Clear form fields
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
            
            // Disable autocomplete
            document.querySelectorAll('input').forEach(input => {
                input.setAttribute('autocomplete', 'off');
                input.setAttribute('readonly', true);
                
                // Remove readonly after focus to prevent autocomplete
                input.addEventListener('focus', function() {
                    this.removeAttribute('readonly');
                });
            });
            
            // Clear any stored values in browser
            if (typeof Storage !== 'undefined') {
                localStorage.removeItem('admin_remember');
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_session');
                sessionStorage.clear();
            }
        });

        // Add floating animation to background elements
        function animateBackground() {
            const elements = document.querySelectorAll('.absolute.w-80, .absolute.w-96, .absolute.w-64');
            elements.forEach((el, index) => {
                const duration = 3000 + (index * 1000);
                const startTime = Date.now();
                
                function animate() {
                    const elapsed = Date.now() - startTime;
                    const progress = (elapsed % duration) / duration;
                    const offset = Math.sin(progress * Math.PI * 2) * 20;
                    
                    el.style.transform = `translate(${offset}px, ${offset * 0.5}px)`;
                    requestAnimationFrame(animate);
                }
                
                animate();
            });
        }

        // Start background animation
        animateBackground();

        // Add input focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Escape to clear form
            if (e.key === 'Escape') {
                document.querySelectorAll('input').forEach(input => input.value = '');
            }
        });

        // PERBAIKAN: Prevent back button after logout
        if (window.location.href.includes('logout=success')) {
            history.pushState(null, null, window.location.href);
            window.addEventListener('popstate', function () {
                history.pushState(null, null, window.location.href);
            });
        }
    </script>

    <style>
        /* Custom animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Glassmorphism effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(12px);
        }
        
        /* Custom focus states */
        input:focus + i {
            color: #6366f1 !important;
        }
        
        /* Button hover effects */
        button:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* PERBAIKAN: Disable autocomplete styling */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
        }
    </style>
</body>
</html>