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
                    <form id="loginForm" class="space-y-6">
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
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-indigo-300"
                                    placeholder="Masukkan username"
                                    autocomplete="username"
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
                                    class="w-full px-4 py-3 pl-12 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 hover:border-indigo-300"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
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

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    id="remember" 
                                    name="remember" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition-colors duration-200"
                                >
                                <label for="remember" class="ml-2 block text-sm text-gray-700 select-none cursor-pointer">
                                    Ingat saya
                                </label>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span id="errorText">Username atau password salah!</span>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <div id="successMessage" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Login berhasil! Mengalihkan...</span>
                            </div>
                        </div>

                        <!-- Login Button -->
                        <button
                            type="submit"
                            id="loginButton"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center"
                        >
                            <span id="loginButtonText">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Masuk ke Dashboard
                            </span>
                            <div id="loginSpinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Memproses...
                            </div>
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
                                    Keamanan Tinggi
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

        // Form submission handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginSpinner = document.getElementById('loginSpinner');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');

            // Hide previous messages
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');

            // Show loading state
            loginButton.disabled = true;
            loginButtonText.classList.add('hidden');
            loginSpinner.classList.remove('hidden');

            // Simulate API call
            setTimeout(() => {
                // Demo credentials validation
                const validCredentials = [
                    { username: 'admin', password: 'admin123', role: 'Super Admin' },
                    { username: 'moderator', password: 'mod123', role: 'Moderator' }
                ];

                const user = validCredentials.find(cred => 
                    cred.username === username && cred.password === password
                );

                if (user) {
                    // Success
                    successMessage.classList.remove('hidden');
                    
                    // Store user data (in real app, use secure token)
                    localStorage.setItem('adminUser', JSON.stringify({
                        username: user.username,
                        role: user.role,
                        loginTime: new Date().toISOString()
                    }));
                    
                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = 'admin.php';
                    }, 1500);
                } else {
                    // Error
                    errorMessage.classList.remove('hidden');
                    
                    // Reset button state
                    loginButton.disabled = false;
                    loginButtonText.classList.remove('hidden');
                    loginSpinner.classList.add('hidden');
                    
                    // Shake animation
                    errorMessage.classList.add('animate__animated', 'animate__shakeX');
                    setTimeout(() => {
                        errorMessage.classList.remove('animate__animated', 'animate__shakeX');
                    }, 1000);
                }
            }, 2000); // Simulate network delay
        });

        // Auto-fill demo credentials on credential click
        document.querySelectorAll('code').forEach(code => {
            code.addEventListener('click', function() {
                const text = this.textContent;
                const isPassword = this.parentElement.textContent.includes('Password');
                
                if (isPassword) {
                    document.getElementById('password').value = text;
                } else {
                    document.getElementById('username').value = text;
                }
                
                // Add visual feedback
                this.classList.add('bg-green-200');
                setTimeout(() => {
                    this.classList.remove('bg-green-200');
                    if (isPassword) {
                        this.classList.add('bg-purple-100');
                    } else {
                        this.classList.add('bg-blue-100');
                    }
                }, 300);
            });
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
            // Ctrl/Cmd + Enter to submit
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
            
            // Escape to clear form
            if (e.key === 'Escape') {
                document.getElementById('loginForm').reset();
                document.getElementById('errorMessage').classList.add('hidden');
                document.getElementById('successMessage').classList.add('hidden');
            }
        });
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
        
        /* Code element hover */
        code {
            cursor: pointer;
            transition: all 0.2s;
        }
        
        code:hover {
            transform: scale(1.05);
        }
    </style>
</body>
</html>