<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
// Include backend functions
require_once 'admin-dashboard-backend.php';
// Get initial data
$stats = getDashboardStats();
$participantsResult = getParticipantsData('', '', '', 1, 10);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hasil Peserta - Psikotest Burnout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <i class="fas fa-brain text-3xl text-indigo-600 mr-3"></i>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                        <p class="text-sm text-gray-600">Burnout Assessment</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500" id="current-date"></p>
                    </div>
                    <button onclick="logout()" class="logout-btn px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Peserta Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Peserta</p>
                                <p class="text-2xl font-bold text-gray-900" id="total-peserta">0</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium bg-green-100 px-2 py-1 rounded-full">
                                <i class="fas fa-arrow-up text-xs"></i> 100%
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>Seluruh peserta yang telah mengikuti tes</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Burnout Rendah Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-smile text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Burnout Rendah</p>
                                <p class="text-2xl font-bold text-gray-900" id="burnout-rendah">0</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-green-500 text-sm font-medium bg-green-100 px-2 py-1 rounded-full" id="persen-rendah">
                                0%
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-heart mr-2"></i>
                            <span>Kondisi mental yang baik</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Burnout Sedang Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-meh text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Burnout Sedang</p>
                                <p class="text-2xl font-bold text-gray-900" id="burnout-sedang">0</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-500 text-sm font-medium bg-yellow-100 px-2 py-1 rounded-full" id="persen-sedang">
                                0%
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>Perlu perhatian khusus</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Burnout Tinggi Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-frown text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Burnout Tinggi</p>
                                <p class="text-2xl font-bold text-gray-900" id="burnout-tinggi">0</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-red-500 text-sm font-medium bg-red-100 px-2 py-1 rounded-full" id="persen-tinggi">
                                0%
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-heartbeat mr-2"></i>
                            <span>Butuh intervensi segera</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Burnout Sangat Tinggi Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-red-700 to-red-900 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-dizzy text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Burnout Sangat Tinggi</p>
                                <p class="text-2xl font-bold text-gray-900" id="burnout-sangat-tinggi">0</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-red-700 text-sm font-medium bg-red-100 px-2 py-1 rounded-full" id="persen-sangat-tinggi">
                                0%
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-ambulance mr-2"></i>
                            <span>Darurat! Perlu bantuan profesional</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 mb-6">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div>
                            <input type="text" id="search-input" placeholder="Cari nama peserta..." 
                                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <select id="filter-category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Semua Kategori</option>
                                <option value="Rendah">Burnout Rendah</option>
                                <option value="Sedang">Burnout Sedang</option>
                                <option value="Tinggi">Burnout Tinggi</option>
                                <option value="Sangat Tinggi">Burnout Sangat Tinggi</option>
                            </select>
                        </div>
                        <div>
                            <select id="filter-gender" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Semua Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="exportData()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>Export Excel
                        </button>
                        <button onclick="refreshData()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Hasil Tes Peserta</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Tes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="results-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Data akan dimuat di sini -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-100 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button onclick="changePage(currentPage - 1)" id="prev-btn" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    <button onclick="changePage(currentPage + 1)" id="next-btn" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium" id="showing-from">1</span> sampai <span class="font-medium" id="showing-to">10</span> dari
                            <span class="font-medium" id="total-results">0</span> hasil
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" id="pagination">
                            <!-- Pagination buttons akan dimuat di sini -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Detail Modal -->
    <div id="detail-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full mx-4">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Hasil Tes</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div id="modal-content">
                    <!-- Detail content akan dimuat di sini -->
                </div>
            </div>
            <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loading-spinner" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>

    <script>
        // Global variables
        let currentPage = 1;
        let totalPages = 1;
        let isLoading = false;
        
        // Initialize dashboard on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadParticipantsData();
            setupEventListeners();
            setCurrentDate();
        });
        
        // Set current date
        function setCurrentDate() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', options);
        }
        
        // Setup event listeners
        function setupEventListeners() {
            // Search input
            const searchInput = document.getElementById('search-input');
            searchInput.addEventListener('input', debounce(function() {
                currentPage = 1;
                loadParticipantsData();
            }, 500));
            
            // Filter dropdowns
            const categoryFilter = document.getElementById('filter-category');
            const genderFilter = document.getElementById('filter-gender');
            
            categoryFilter.addEventListener('change', function() {
                currentPage = 1;
                loadParticipantsData();
            });
            
            genderFilter.addEventListener('change', function() {
                currentPage = 1;
                loadParticipantsData();
            });
        }
        
        // Debounce function for search
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // Load dashboard statistics
        async function loadDashboardStats() {
            try {
                const response = await fetch('admin-dashboard-backend.php?action=get_stats');
                const stats = await response.json();
                
                // Update statistics cards
                document.getElementById('total-peserta').textContent = stats.total_peserta || 0;
                document.getElementById('burnout-rendah').textContent = stats.burnout_rendah || 0;
                document.getElementById('burnout-sedang').textContent = stats.burnout_sedang || 0;
                document.getElementById('burnout-tinggi').textContent = stats.burnout_tinggi || 0;
                document.getElementById('burnout-sangat-tinggi').textContent = stats.burnout_sangat_tinggi || 0;
                
                // Calculate and update percentages
                const total = stats.total_peserta || 1; // Avoid division by zero
                document.getElementById('persen-rendah').textContent = 
                    Math.round((stats.burnout_rendah / total) * 100) + '%';
                document.getElementById('persen-sedang').textContent = 
                    Math.round((stats.burnout_sedang / total) * 100) + '%';
                document.getElementById('persen-tinggi').textContent = 
                    Math.round((stats.burnout_tinggi / total) * 100) + '%';
                document.getElementById('persen-sangat-tinggi').textContent = 
                    Math.round((stats.burnout_sangat_tinggi / total) * 100) + '%';
                    
            } catch (error) {
                console.error('Error loading dashboard stats:', error);
                showNotification('Gagal memuat statistik dashboard', 'error');
            }
        }
        
        // Load participants data
        async function loadParticipantsData() {
            if (isLoading) return;
            
            isLoading = true;
            showLoading(true);
            
            try {
                const search = document.getElementById('search-input').value;
                const category = document.getElementById('filter-category').value;
                const gender = document.getElementById('filter-gender').value;
                
                const params = new URLSearchParams({
                    action: 'get_participants',
                    search: search,
                    category: category,
                    gender: gender,
                    page: currentPage,
                    limit: 10
                });
                
                const response = await fetch('admin-dashboard-backend.php?' + params);
                const result = await response.json();
                
                // Update table
                updateParticipantsTable(result.data);
                
                // Update pagination
                totalPages = result.total_pages;
                updatePagination(result.current_page, result.total_pages, result.total);
                
            } catch (error) {
                console.error('Error loading participants data:', error);
                showNotification('Gagal memuat data peserta', 'error');
            } finally {
                isLoading = false;
                showLoading(false);
            }
        }
        
        // Update participants table
        function updateParticipantsTable(data) {
            const tbody = document.getElementById('results-tbody');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data yang ditemukan
                        </td>
                    </tr>
                `;
                return;
            }
            
            data.forEach((participant, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                
                const categoryClass = getCategoryClass(participant.kategori);
                const startIndex = (currentPage - 1) * 10 + 1;
                
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${startIndex + index}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${participant.nama}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${participant.jenis_kelamin}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${participant.usia} tahun
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${participant.pekerjaan}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                        ${participant.total_skor}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${categoryClass}">
                            ${participant.kategori}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${formatDate(participant.tanggal_tes)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="showDetail(${participant.id})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }
        
        // Get category class for styling
        function getCategoryClass(category) {
            switch (category) {
                case 'Rendah':
                    return 'bg-green-100 text-green-800';
                case 'Sedang':
                    return 'bg-yellow-100 text-yellow-800';
                case 'Tinggi':
                    return 'bg-red-100 text-red-800';
                case 'Sangat Tinggi':
                    return 'bg-red-200 text-red-900';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }
        
        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
        
        // Update pagination
        function updatePagination(current, total, totalResults) {
            const showingFrom = (current - 1) * 10 + 1;
            const showingTo = Math.min(current * 10, totalResults);
            
            document.getElementById('showing-from').textContent = showingFrom;
            document.getElementById('showing-to').textContent = showingTo;
            document.getElementById('total-results').textContent = totalResults;
            
            // Update pagination buttons
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            
            // Previous button
            if (current > 1) {
                const prevBtn = createPaginationButton(current - 1, 'Previous');
                pagination.appendChild(prevBtn);
            }
            
            // Page numbers
            const startPage = Math.max(1, current - 2);
            const endPage = Math.min(total, current + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = createPaginationButton(i, i.toString(), i === current);
                pagination.appendChild(pageBtn);
            }
            
            // Next button
            if (current < total) {
                const nextBtn = createPaginationButton(current + 1, 'Next');
                pagination.appendChild(nextBtn);
            }
        }
        
        // Create pagination button
        function createPaginationButton(page, text, isActive = false) {
            const button = document.createElement('button');
            button.onclick = () => changePage(page);
            button.className = `relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                isActive 
                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
            }`;
            button.textContent = text;
            return button;
        }
        
        // Change page
        function changePage(page) {
            if (page < 1 || page > totalPages || page === currentPage) return;
            currentPage = page;
            loadParticipantsData();
        }
        
        // Show participant detail
        async function showDetail(id) {
            try {
                showLoading(true);
                
                const formData = new FormData();
                formData.append('action', 'get_detail');
                formData.append('id', id);
                
                const response = await fetch('admin-dashboard-backend.php', {
                    method: 'POST',
                    body: formData
                });
                
                const participant = await response.json();
                
                if (participant) {
                    showDetailModal(participant);
                } else {
                    showNotification('Data peserta tidak ditemukan', 'error');
                }
                
            } catch (error) {
                console.error('Error loading participant detail:', error);
                showNotification('Gagal memuat detail peserta', 'error');
            } finally {
                showLoading(false);
            }
        }
        
        // Show detail modal
        function showDetailModal(participant) {
            const modalContent = document.getElementById('modal-content');
            
            const categoryClass = getCategoryClass(participant.kategori);
            const jawabanDetails = participant.jawaban_details || {};
            
            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informasi Peserta</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama Lengkap</label>
                                <p class="text-sm text-gray-900">${participant.nama}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Jenis Kelamin</label>
                                <p class="text-sm text-gray-900">${participant.jenis_kelamin}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Usia</label>
                                <p class="text-sm text-gray-900">${participant.usia} tahun</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Pekerjaan</label>
                                <p class="text-sm text-gray-900">${participant.pekerjaan}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Pendidikan</label>
                                <p class="text-sm text-gray-900">${participant.pendidikan}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Tanggal Tes</label>
                                <p class="text-sm text-gray-900">${formatDate(participant.tanggal_tes)}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Hasil Tes</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Jawaban "Ya"</label>
                                <p class="text-sm text-gray-900">${participant.total_jawaban_ya}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Total Skor</label>
                                <p class="text-sm text-gray-900 font-medium">${participant.total_skor}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Persentase</label>
                                <p class="text-sm text-gray-900">${participant.persentase}%</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Kategori Burnout</label>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${categoryClass}">
                                    ${participant.kategori}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h5 class="font-medium text-gray-900 mb-2">Analisis Jawaban</h5>
                            <div class="text-sm text-gray-600">
                                <p>Total soal dijawab: ${jawabanDetails.total_soal || 'N/A'}</p>
                                <p>Jawaban "Ya": ${jawabanDetails.total_ya || 'N/A'}</p>
                                <p>Jawaban "Tidak": ${(jawabanDetails.total_soal || 0) - (jawabanDetails.total_ya || 0)}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('detail-modal').classList.remove('hidden');
            document.getElementById('detail-modal').classList.add('flex');
        }
        
        // Close detail modal
        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.getElementById('detail-modal').classList.remove('flex');
        }
        
        // Show/hide loading spinner
        function showLoading(show) {
            const spinner = document.getElementById('loading-spinner');
            if (show) {
                spinner.classList.remove('hidden');
                spinner.classList.add('flex');
            } else {
                spinner.classList.add('hidden');
                spinner.classList.remove('flex');
            }
        }
        
        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'error' ? 'bg-red-500 text-white' : 'bg-green-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Refresh data
        function refreshData() {
            loadDashboardStats();
            loadParticipantsData();
            showNotification('Data berhasil diperbarui', 'success');
        }
        
        // Logout function
        function logout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                window.location.href = 'index.html?logout=1';
            }
        }
        
        // Export data function
       async function exportData() {
    try {
        showLoading(true);
        
        const search = document.getElementById('search-input').value;
        const category = document.getElementById('filter-category').value;
        const gender = document.getElementById('filter-gender').value;
        
        const params = new URLSearchParams({
            action: 'export_data',
            search: search,
            category: category,
            gender: gender
        });
        
        const response = await fetch('admin-dashboard-backend.php?' + params);
        const data = await response.json();
        
        if (data.success && data.data) {
            // Buat workbook baru
            const workbook = XLSX.utils.book_new();
            
            // Siapkan data untuk worksheet
            const worksheetData = [
                ['No', 'Nama Peserta', 'Jenis Kelamin', 'Usia', 'Pekerjaan', 'Pendidikan', 'Total Skor', 'Persentase', 'Kategori Burnout', 'Tanggal Tes']
            ];
            
            data.data.forEach((participant, index) => {
                worksheetData.push([
                    index + 1,
                    participant.nama,
                    participant.jenis_kelamin,
                    participant.usia,
                    participant.pekerjaan,
                    participant.pendidikan,
                    participant.total_skor,
                    participant.persentase + '%',
                    participant.kategori,
                    formatDate(participant.tanggal_tes)
                ]);
            });
            
            // Buat worksheet
            const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
            
            // Set column widths
            const columnWidths = [
                { wch: 5 },   // No
                { wch: 20 },  // Nama
                { wch: 15 },  // Jenis Kelamin
                { wch: 8 },   // Usia
                { wch: 20 },  // Pekerjaan
                { wch: 15 },  // Pendidikan
                { wch: 12 },  // Total Skor
                { wch: 12 },  // Persentase
                { wch: 18 },  // Kategori
                { wch: 15 }   // Tanggal Tes
            ];
            worksheet['!cols'] = columnWidths;
            
            // Tambahkan worksheet ke workbook
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Data Peserta');
            
            // Generate nama file
            const fileName = `hasil-peserta-burnout-${new Date().toISOString().split('T')[0]}.xlsx`;
            
            // Download file
            XLSX.writeFile(workbook, fileName);
            
            showNotification('Data berhasil diexport ke Excel', 'success');
            
        } else {
            showNotification('Tidak ada data untuk diexport', 'error');
        }
        
    } catch (error) {
        console.error('Error exporting data:', error);
        showNotification('Gagal mengexport data', 'error');
    } finally {
        showLoading(false);
    }
}
    </script>
</body>
</html>