<?php
// admin-dashboard-backend.php (No Auto Login Version)

// Cek apakah session sudah dimulai, jika belum baru start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include config database
require_once 'config.php';

// Fungsi untuk validasi session admin yang lebih ketat
function validateAdminSession() {
    // Cek apakah admin sudah login
    if (!isset($_SESSION['admin_id'])) {
        return false;
    }
    
    // Cek apakah session memiliki login_time
    if (!isset($_SESSION['login_time'])) {
        return false;
    }
    
    // Cek apakah session sudah expired (misal 8 jam)
    $login_time = strtotime($_SESSION['login_time']);
    $current_time = time();
    $session_duration = 8 * 60 * 60; // 8 jam dalam detik
    
    if (($current_time - $login_time) > $session_duration) {
        // Session expired, hapus session dan redirect
        session_destroy();
        return false;
    }
    
    return true;
}

// Validasi session admin - redirect jika tidak valid
if (!validateAdminSession()) {
    // Hapus semua session data dan cookie
    session_destroy();
    
    // Hapus cookie remember me jika ada
    if (isset($_COOKIE['admin_remember'])) {
        setcookie('admin_remember', '', time() - 3600, '/');
        setcookie('admin_remember', '', time() - 3600, '/', $_SERVER['HTTP_HOST']);
    }
    
    header('Location: admin-login.php');
    exit();
}

// Fungsi untuk mendapatkan statistik dashboard
function getDashboardStats() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM view_statistik_burnout LIMIT 1");
        $stats = $stmt->fetch();
        
        if (!$stats) {
            // Jika view kosong, return default values
            return [
                'total_peserta' => 0,
                'burnout_rendah' => 0,
                'burnout_sedang' => 0,
                'burnout_tinggi' => 0,
                'burnout_sangat_tinggi' => 0,
                'rata_rata_persentase' => 0
            ];
        }
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Error getting dashboard stats: " . $e->getMessage());
        return [
            'total_peserta' => 0,
            'burnout_rendah' => 0,
            'burnout_sedang' => 0,
            'burnout_tinggi' => 0,
            'burnout_sangat_tinggi' => 0,
            'rata_rata_persentase' => 0
        ];
    }
}

// Fungsi untuk mendapatkan data peserta dengan filter dan pagination
function getParticipantsData($search = '', $category = '', $gender = '', $page = 1, $limit = 10) {
    global $pdo;
    
    try {
        $offset = ($page - 1) * $limit;
        
        // Base query
        $whereClause = "WHERE 1=1";
        $params = [];
        
        // Filter pencarian nama
        if (!empty($search)) {
            $whereClause .= " AND (nama LIKE :search OR pekerjaan LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        // Filter kategori
        if (!empty($category)) {
            $whereClause .= " AND kategori = :category";
            $params[':category'] = $category;
        }
        
        // Filter jenis kelamin
        if (!empty($gender)) {
            $whereClause .= " AND jenis_kelamin = :gender";
            $params[':gender'] = $gender;
        }
        
        // Query untuk mendapatkan total data
        $countQuery = "SELECT COUNT(*) as total FROM view_hasil_lengkap $whereClause";
        $countStmt = $pdo->prepare($countQuery);
        $countStmt->execute($params);
        $totalData = $countStmt->fetch()['total'];
        
        // Query untuk mendapatkan data dengan pagination
        $dataQuery = "SELECT * FROM view_hasil_lengkap $whereClause ORDER BY completed_at DESC LIMIT :limit OFFSET :offset";
        $dataStmt = $pdo->prepare($dataQuery);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $dataStmt->bindValue($key, $value);
        }
        $dataStmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $dataStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $dataStmt->execute();
        $data = $dataStmt->fetchAll();
        
        return [
            'data' => $data,
            'total' => $totalData,
            'current_page' => $page,
            'total_pages' => ceil($totalData / $limit)
        ];
        
    } catch (PDOException $e) {
        error_log("Error getting participants data: " . $e->getMessage());
        return [
            'data' => [],
            'total' => 0,
            'current_page' => 1,
            'total_pages' => 0
        ];
    }
}

// Fungsi untuk mendapatkan detail peserta
function getParticipantDetail($id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM view_hasil_lengkap WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $participant = $stmt->fetch();
        
        if ($participant) {
            // Ambil detail jawaban untuk breakdown (opsional)
            $answerStmt = $pdo->prepare("
                SELECT 
                    COUNT(CASE WHEN jawaban = 'YA' THEN 1 END) as total_ya,
                    COUNT(*) as total_soal
                FROM jawaban_peserta 
                WHERE peserta_id = :peserta_id
            ");
            $answerStmt->execute([':peserta_id' => $id]);
            $answerDetails = $answerStmt->fetch();
            
            $participant['jawaban_details'] = $answerDetails;
        }
        
        return $participant;
        
    } catch (PDOException $e) {
        error_log("Error getting participant detail: " . $e->getMessage());
        return null;
    }
}

// Fungsi untuk export data
function getExportData($search = '', $category = '', $gender = '') {
    global $pdo;
    
    try {
        // Base query
        $whereClause = "WHERE 1=1";
        $params = [];
        
        // Filter pencarian nama
        if (!empty($search)) {
            $whereClause .= " AND (nama LIKE :search OR pekerjaan LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }
        
        // Filter kategori
        if (!empty($category)) {
            $whereClause .= " AND kategori = :category";
            $params[':category'] = $category;
        }
        
        // Filter jenis kelamin
        if (!empty($gender)) {
            $whereClause .= " AND jenis_kelamin = :gender";
            $params[':gender'] = $gender;
        }
        
        // Query untuk mendapatkan semua data sesuai filter
        $query = "SELECT * FROM view_hasil_lengkap $whereClause ORDER BY completed_at DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $data = $stmt->fetchAll();
        
        return [
            'success' => true,
            'data' => $data
        ];
        
    } catch (PDOException $e) {
        error_log("Error getting export data: " . $e->getMessage());
        return [
            'success' => false,
            'data' => []
        ];
    }
}

// Fungsi untuk admin logout yang lebih aman
function adminLogout() {
    global $pdo;
    
    // Log aktivitas logout jika ada admin_id
    if (isset($_SESSION['admin_id'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_SESSION['admin_id'],
                'LOGOUT',
                'Admin logout dari IP: ' . $_SERVER['REMOTE_ADDR'],
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT']
            ]);
        } catch (PDOException $e) {
            error_log("Error logging admin logout: " . $e->getMessage());
        }
        
        // Hapus semua remember me tokens untuk admin ini
        try {
            $stmt = $pdo->prepare("DELETE FROM admin_remember_tokens WHERE admin_id = ?");
            $stmt->execute([$_SESSION['admin_id']]);
        } catch (PDOException $e) {
            error_log("Error deleting remember tokens: " . $e->getMessage());
        }
    }
    
    // Hapus semua session data
    session_destroy();
    
    // Hapus cookie remember me jika ada
    if (isset($_COOKIE['admin_remember'])) {
        setcookie('admin_remember', '', time() - 3600, '/');
        setcookie('admin_remember', '', time() - 3600, '/', $_SERVER['HTTP_HOST']);
    }
    
    // Redirect ke login dengan parameter timestamp untuk mencegah cache
    header('Location: admin-login.php?logout=success&t=' . time());
    exit();
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'get_stats':
            header('Content-Type: application/json');
            echo json_encode(getDashboardStats());
            exit;
            
        case 'get_participants':
            $search = sanitize_input($_POST['search'] ?? '');
            $category = sanitize_input($_POST['category'] ?? '');
            $gender = sanitize_input($_POST['gender'] ?? '');
            $page = intval($_POST['page'] ?? 1);
            $limit = intval($_POST['limit'] ?? 10);
            
            header('Content-Type: application/json');
            echo json_encode(getParticipantsData($search, $category, $gender, $page, $limit));
            exit;
            
        case 'get_detail':
            $id = intval($_POST['id'] ?? 0);
            header('Content-Type: application/json');
            echo json_encode(getParticipantDetail($id));
            exit;
            
        default:
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid action']);
            exit;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'get_stats':
            header('Content-Type: application/json');
            echo json_encode(getDashboardStats());
            exit;
            
        case 'get_participants':
            $search = sanitize_input($_GET['search'] ?? '');
            $category = sanitize_input($_GET['category'] ?? '');
            $gender = sanitize_input($_GET['gender'] ?? '');
            $page = intval($_GET['page'] ?? 1);
            $limit = intval($_GET['limit'] ?? 10);
            
            header('Content-Type: application/json');
            echo json_encode(getParticipantsData($search, $category, $gender, $page, $limit));
            exit;
            
        case 'export_data':
            $search = sanitize_input($_GET['search'] ?? '');
            $category = sanitize_input($_GET['category'] ?? '');
            $gender = sanitize_input($_GET['gender'] ?? '');
            
            header('Content-Type: application/json');
            echo json_encode(getExportData($search, $category, $gender));
            exit;
            
        case 'logout':
            adminLogout();
            exit;
            
        default:
            // Jika tidak ada action, tampilkan dashboard
            include 'admin-dashboard.php';
            exit;
    }
}

// Handle logout dari URL parameter
if (isset($_GET['logout'])) {
    adminLogout();
}
?>