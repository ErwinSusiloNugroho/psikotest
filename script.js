// Fungsi untuk memulai test (untuk halaman landing)
function mulaiTest() {
    // Animasi loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Memuat...';
    button.disabled = true;
    
    // Redirect ke halaman form identitas
    setTimeout(() => {
        window.location.href = 'from-identitas.php';
    }, 1500);
}

// Smooth scrolling dan animasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Animasi fade in untuk elemen
    const container = document.querySelector('.container');
    if (container) {
        const elements = container.children;
        Array.from(elements).forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            setTimeout(() => {
                el.style.transition = 'all 0.6s ease';
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 200);
        });
    }
    
    // Inisialisasi form identitas jika ada
    const identitasForm = document.getElementById('identitasForm');
    if (identitasForm) {
        initIdentitasForm();
    }
    
    // Reset border color when user starts typing
    document.querySelectorAll('input, select').forEach(element => {
        element.addEventListener('input', function() {
            this.style.borderColor = '';
            // Hapus pesan error jika ada
            const errorMsg = this.parentNode.querySelector('.error-msg');
            if (errorMsg) {
                errorMsg.remove();
            }
        });
    });
});

// Inisialisasi form identitas
function initIdentitasForm() {
    const form = document.getElementById('identitasForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Hapus semua pesan error sebelumnya
        document.querySelectorAll('.error-msg').forEach(msg => msg.remove());
        
        // Validasi form
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        
        // Validasi field wajib dan tampilkan error
        let isValid = true;
        const errors = [];
        
        // Validasi nama
        if (!data.nama || data.nama.trim() === '') {
            isValid = false;
            showFieldError('nama', 'Nama lengkap harus diisi');
            errors.push('Nama lengkap harus diisi');
        }
        
        // Validasi jenis kelamin
        if (!data.jenis_kelamin) {
            isValid = false;
            errors.push('Jenis kelamin harus dipilih');
            // Highlight radio button container
            const genderContainer = document.querySelector('input[name="jenis_kelamin"]').closest('div');
            genderContainer.style.borderColor = '#ef4444';
        }
        
        // Validasi usia
        const usia = parseInt(data.usia);
        if (!data.usia || isNaN(usia)) {
            isValid = false;
            showFieldError('usia', 'Usia harus diisi');
            errors.push('Usia harus diisi');
        } else if (usia < 15 || usia > 70) {
            isValid = false;
            showFieldError('usia', 'Usia harus antara 15-70 tahun');
            errors.push('Usia harus antara 15-70 tahun');
        }
        
        // Validasi pekerjaan
        if (!data.pekerjaan || data.pekerjaan.trim() === '') {
            isValid = false;
            showFieldError('pekerjaan', 'Pekerjaan harus diisi');
            errors.push('Pekerjaan harus diisi');
        }
        
        // Validasi pendidikan
        if (!data.pendidikan) {
            isValid = false;
            showFieldError('pendidikan', 'Pendidikan terakhir harus dipilih');
            errors.push('Pendidikan terakhir harus dipilih');
        }
        
        // Validasi agreement
        if (!data.agreement) {
            isValid = false;
            errors.push('Anda harus menyetujui persyaratan');
            const agreementLabel = document.querySelector('label[for="agreement"]');
            agreementLabel.style.color = '#ef4444';
        }
        
        if (!isValid) {
            // Tampilkan ringkasan error
            showErrorSummary(errors);
            return;
        }
        
        // Jika valid, submit form dengan PHP
        submitForm(this, data);
    });
}

// Tampilkan error pada field tertentu
function showFieldError(fieldName, message) {
    const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
    if (field) {
        field.style.borderColor = '#ef4444';
        field.focus();
        
        // Buat pesan error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-msg text-red-600 text-sm mt-1';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
        
        // Insert setelah field
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
    }
}

// Tampilkan ringkasan error
function showErrorSummary(errors) {
    // Hapus error summary yang ada
    const existingError = document.querySelector('.error-summary');
    if (existingError) {
        existingError.remove();
    }
    
    // Buat error summary
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-summary bg-red-50 border border-red-200 rounded-lg p-4 mb-6';
    
    let errorHTML = `
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-1"></i>
            <div>
                <h4 class="font-semibold text-red-800 mb-2">Mohon perbaiki kesalahan berikut:</h4>
                <ul class="text-red-700 text-sm space-y-1">
    `;
    
    errors.forEach(error => {
        errorHTML += `<li>• ${error}</li>`;
    });
    
    errorHTML += `
                </ul>
            </div>
        </div>
    `;
    
    errorDiv.innerHTML = errorHTML;
    
    // Insert di atas form
    const form = document.getElementById('identitasForm');
    form.parentNode.insertBefore(errorDiv, form);
    
    // Scroll ke error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Submit form ke PHP
function submitForm(form, data) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Memproses...';
    submitBtn.disabled = true;
    
    // Buat form baru untuk submit ke PHP
    const phpForm = document.createElement('form');
    phpForm.method = 'POST';
    phpForm.action = window.location.href; // Submit ke file yang sama
    
    // Tambahkan semua data sebagai hidden input
    Object.keys(data).forEach(key => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = data[key];
        phpForm.appendChild(input);
    });
    
    // Append form ke body dan submit
    document.body.appendChild(phpForm);
    phpForm.submit();
}

// Reset field styling saat user mulai mengetik
function resetFieldStyling(field) {
    field.style.borderColor = '';
    const errorMsg = field.parentNode.querySelector('.error-msg');
    if (errorMsg) {
        errorMsg.remove();
    }
}

// Format nomor telepon (jika diperlukan di masa depan)
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.startsWith('0')) {
        input.value = value;
    } else if (value.startsWith('8')) {
        input.value = '0' + value;
    }
}

// Utility functions
function showSuccess(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
    
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

function showLoading(element, originalText) {
    element.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Memproses...';
    element.disabled = true;
    
    return () => {
        element.innerHTML = originalText;
        element.disabled = false;
    };
}

// Fungsi untuk memulai test (redirect ke udashboard.html)
function mulaiTest(event) {
    const button = event.target;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat...';
    button.disabled = true;
    
    // Redirect ke udashboard.html
    setTimeout(() => {
        window.location.href = 'udashboard.html';
    }, 1500);
}

// Animasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Animasi fade in untuk elemen
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach((el, index) => {
        el.style.animationDelay = (index * 0.2) + 's';
    });
    
    // Auto-show login modal jika ada error (untuk PHP integration)
    // Note: Bagian PHP ini perlu ditangani di sisi server atau melalui URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('error')) {
        // Pastikan Bootstrap modal tersedia
        if (typeof bootstrap !== 'undefined') {
            const loginModalElement = document.getElementById('loginModal');
            if (loginModalElement) {
                const loginModal = new bootstrap.Modal(loginModalElement);
                loginModal.show();
            }
        }
    }
    
    // Initialize team photo hover effects
    initTeamPhotoEffects();
    
    // Initialize scroll effects
    initScrollEffects();
});

// Efek hover pada team photo
function initTeamPhotoEffects() {
    const teamPhoto = document.querySelector('.team-photo');
    if (teamPhoto) {
        teamPhoto.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotateY(5deg)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        teamPhoto.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotateY(0deg)';
            this.style.transition = 'transform 0.3s ease';
        });
    }
}

// Smooth scrolling untuk animasi yang lebih halus
function initScrollEffects() {
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.bg-animation');
        if (parallax) {
            parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
}

// Utility functions
const Utils = {
    // Fungsi untuk menampilkan loading state pada button
    setButtonLoading: function(button, isLoading = true, loadingText = 'Memuat...') {
        if (isLoading) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${loadingText}`;
            button.disabled = true;
        } else {
            button.innerHTML = button.dataset.originalText || button.innerHTML;
            button.disabled = false;
        }
    },
    
    // Fungsi untuk smooth scroll ke element
    smoothScrollTo: function(element, offset = 0) {
        const elementPosition = element.offsetTop - offset;
        window.scrollTo({
            top: elementPosition,
            behavior: 'smooth'
        });
    },
    
    // Fungsi untuk fade in animation
    fadeIn: function(element, duration = 300) {
        element.style.opacity = 0;
        element.style.display = 'block';
        
        const start = performance.now();
        
        function animate(currentTime) {
            const elapsed = currentTime - start;
            const progress = elapsed / duration;
            
            if (progress < 1) {
                element.style.opacity = progress;
                requestAnimationFrame(animate);
            } else {
                element.style.opacity = 1;
            }
        }
        
        requestAnimationFrame(animate);
    }
};

// Export functions for global access (if needed)
window.mulaiTest = mulaiTest;
window.Utils = Utils;
