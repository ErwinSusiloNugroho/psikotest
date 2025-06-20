<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hasil Peserta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <i class="fas fa-chart-line text-2xl text-blue-600 mr-3"></i>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Dashboard Hasil Peserta</h1>
                        <p class="text-sm text-gray-600">Psikotest Burnout</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500" id="current-date"></p>
                    </div>
                    <button onclick="logout()" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 shadow-sm border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Peserta</p>
                        <p class="text-2xl font-semibold text-gray-900" id="total-count">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-smile text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Burnout Rendah</p>
                        <p class="text-2xl font-semibold text-gray-900" id="low-count">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-meh text-yellow-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Burnout Sedang</p>
                        <p class="text-2xl font-semibold text-gray-900" id="medium-count">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-frown text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Burnout Tinggi</p>
                        <p class="text-2xl font-semibold text-gray-900" id="high-count">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-lg shadow-sm border mb-6">
            <div class="p-6 border-b">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div>
                            <input type="text" id="search-input" placeholder="Cari nama peserta..." 
                                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <select id="filter-category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Kategori</option>
                                <option value="Rendah">Burnout Rendah</option>
                                <option value="Sedang">Burnout Sedang</option>
                                <option value="Tinggi">Burnout Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="exportData()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                        <button onclick="refreshData()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Hasil Tes Peserta</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
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
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <div class="p-6 border-b">
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
            <div class="p-6 border-t bg-gray-50 flex justify-end">
                <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sample data
        let participantsData = [
            { id: 1, name: "Ahmad Rizki", email: "ahmad.rizki@email.com", score: 42, category: "Rendah", date: "2024-06-15", details: { emotional: 15, depersonalization: 12, achievement: 15 } },
            { id: 2, name: "Siti Nurhaliza", email: "siti.nur@email.com", score: 68, category: "Sedang", date: "2024-06-14", details: { emotional: 25, depersonalization: 20, achievement: 23 } },
            { id: 3, name: "Budi Santoso", email: "budi.santoso@email.com", score: 89, category: "Tinggi", date: "2024-06-13", details: { emotional: 32, depersonalization: 28, achievement: 29 } },
            { id: 4, name: "Maya Sari", email: "maya.sari@email.com", score: 35, category: "Rendah", date: "2024-06-12", details: { emotional: 12, depersonalization: 10, achievement: 13 } },
            { id: 5, name: "Joko Widodo", email: "joko.w@email.com", score: 75, category: "Sedang", date: "2024-06-11", details: { emotional: 27, depersonalization: 23, achievement: 25 } },
            { id: 6, name: "Indira Kenz", email: "indira.k@email.com", score: 92, category: "Tinggi", date: "2024-06-10", details: { emotional: 35, depersonalization: 30, achievement: 27 } },
            { id: 7, name: "Rudi Tabuti", email: "rudi.t@email.com", score: 38, category: "Rendah", date: "2024-06-09", details: { emotional: 13, depersonalization: 12, achievement: 13 } },
            { id: 8, name: "Lisa Blackpink", email: "lisa.bp@email.com", score: 71, category: "Sedang", date: "2024-06-08", details: { emotional: 26, depersonalization: 22, achievement: 23 } },
            { id: 9, name: "Andi Malarangeng", email: "andi.m@email.com", score: 85, category: "Tinggi", date: "2024-06-07", details: { emotional: 30, depersonalization: 27, achievement: 28 } },
            { id: 10, name: "Dewi Persik", email: "dewi.p@email.com", score: 41, category: "Rendah", date: "2024-06-06", details: { emotional: 14, depersonalization: 13, achievement: 14 } }
        ];

        let filteredData = [...participantsData];
        let currentPage = 1;
        const itemsPerPage = 10;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            setCurrentDate();
            updateSummaryCards();
            renderTable();
            setupEventListeners();
        });

        function setCurrentDate() {
            const now = new Date();
            document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        function updateSummaryCards() {
            const total = filteredData.length;
            const low = filteredData.filter(p => p.category === 'Rendah').length;
            const medium = filteredData.filter(p => p.category === 'Sedang').length;
            const high = filteredData.filter(p => p.category === 'Tinggi').length;

            document.getElementById('total-count').textContent = total;
            document.getElementById('low-count').textContent = low;
            document.getElementById('medium-count').textContent = medium;
            document.getElementById('high-count').textContent = high;
        }

        function getCategoryBadge(category) {
            const badges = {
                'Rendah': 'bg-green-100 text-green-800',
                'Sedang': 'bg-yellow-100 text-yellow-800',
                'Tinggi': 'bg-red-100 text-red-800'
            };
            return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${badges[category]}">${category}</span>`;
        }

        function renderTable() {
            const tbody = document.getElementById('results-tbody');
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);

            tbody.innerHTML = pageData.map((participant, index) => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${startIndex + index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${participant.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${participant.email}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">${participant.score}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">${getCategoryBadge(participant.category)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(participant.date).toLocaleDateString('id-ID')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="showDetail(${participant.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                    </td>
                </tr>
            `).join('');

            updatePaginationInfo();
        }

        function updatePaginationInfo() {
            const total = filteredData.length;
            const startIndex = (currentPage - 1) * itemsPerPage + 1;
            const endIndex = Math.min(currentPage * itemsPerPage, total);

            document.getElementById('showing-from').textContent = total > 0 ? startIndex : 0;
            document.getElementById('showing-to').textContent = endIndex;
            document.getElementById('total-results').textContent = total;
        }

        function setupEventListeners() {
            // Search
            document.getElementById('search-input').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filteredData = participantsData.filter(p => 
                    p.name.toLowerCase().includes(searchTerm) || 
                    p.email.toLowerCase().includes(searchTerm)
                );
                applyFilter();
            });

            // Filter
            document.getElementById('filter-category').addEventListener('change', function(e) {
                const category = e.target.value;
                if (category) {
                    filteredData = filteredData.filter(p => p.category === category);
                }
                currentPage = 1;
                updateSummaryCards();
                renderTable();
            });
        }

        function applyFilter() {
            const category = document.getElementById('filter-category').value;
            if (category) {
                filteredData = filteredData.filter(p => p.category === category);
            }
            currentPage = 1;
            updateSummaryCards();
            renderTable();
        }

        function showDetail(id) {
            const participant = participantsData.find(p => p.id === id);
            if (!participant) return;

            const modalContent = document.getElementById('modal-content');
            modalContent.innerHTML = `
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Peserta</h4>
                            <div class="space-y-2">
                                <p><span class="font-medium">Nama:</span> ${participant.name}</p>
                                <p><span class="font-medium">Email:</span> ${participant.email}</p>
                                <p><span class="font-medium">Tanggal Tes:</span> ${new Date(participant.date).toLocaleDateString('id-ID')}</p>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Hasil Tes</h4>
                            <div class="space-y-2">
                                <p><span class="font-medium">Skor Total:</span> ${participant.score}</p>
                                <p><span class="font-medium">Kategori:</span> ${getCategoryBadge(participant.category)}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Detail Skor per Dimensi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <p class="text-sm text-blue-600 font-medium">Kelelahan Emosional</p>
                                <p class="text-2xl font-bold text-blue-800">${participant.details.emotional}</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <p class="text-sm text-purple-600 font-medium">Depersonalisasi</p>
                                <p class="text-2xl font-bold text-purple-800">${participant.details.depersonalization}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <p class="text-sm text-green-600 font-medium">Pencapaian Pribadi</p>
                                <p class="text-2xl font-bold text-green-800">${participant.details.achievement}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('detail-modal').classList.remove('hidden');
            document.getElementById('detail-modal').classList.add('flex');
        }

        function closeDetailModal() {
            document.getElementById('detail-modal').classList.add('hidden');
            document.getElementById('detail-modal').classList.remove('flex');
        }

        function exportData() {
            // Simulate export
            const csv = [
                ['No', 'Nama', 'Email', 'Skor', 'Kategori', 'Tanggal'],
                ...filteredData.map((p, i) => [i+1, p.name, p.email, p.score, p.category, p.date])
            ].map(row => row.join(',')).join('\n');

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'hasil-peserta.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function refreshData() {
            // Reset filters
            document.getElementById('search-input').value = '';
            document.getElementById('filter-category').value = '';
            filteredData = [...participantsData];
            currentPage = 1;
            updateSummaryCards();
            renderTable();
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                // Hapus session/token jika ada
                localStorage.removeItem('admin_token');
                sessionStorage.clear();
                
                // Redirect ke halaman login atau beranda
                window.location.href = 'index.php'; // Ganti dengan URL login page Anda
            }
        }
    </script>
</body>
</html>