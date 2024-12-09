<?php
session_start();

// Database connection
$db_host = 'localhost';
$db_user = 'your_username';
$db_pass = 'your_password';
$db_name = 'your_database';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $country_code = $_POST['country_code'];
    $phone = $_POST['phone'];
    
    // Sanitize input
    $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    $full_phone = $country_code . $phone;
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
    $stmt->bind_param("s", $full_phone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User exists - generate OTP
        $otp = rand(100000, 999999);
        $otp_hash = password_hash($otp, PASSWORD_DEFAULT);
        
        // Store OTP in database
        $stmt = $conn->prepare("UPDATE users SET otp_hash = ?, otp_expiry = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE phone = ?");
        $stmt->bind_param("ss", $otp_hash, $full_phone);
        $stmt->execute();
        
        // Send OTP via SMS (implement your SMS gateway here)
        // sendSMS($full_phone, "Your OTP is: " . $otp);
        
        $_SESSION['phone'] = $full_phone;
        header("Location: verify_otp.php");
    } else {
        // New user - register
        $stmt = $conn->prepare("INSERT INTO users (phone, created_at) VALUES (?, NOW())");
        $stmt->bind_param("s", $full_phone);
        $stmt->execute();
        
        // Generate and send OTP
        $otp = rand(100000, 999999);
        $otp_hash = password_hash($otp, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE users SET otp_hash = ?, otp_expiry = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE phone = ?");
        $stmt->bind_param("ss", $otp_hash, $full_phone);
        $stmt->execute();
        
        // Send OTP via SMS (implement your SMS gateway here)
        // sendSMS($full_phone, "Your OTP is: " . $otp);
        
        $_SESSION['phone'] = $full_phone;
        header("Location: verify_otp.php");
    }
    
    $stmt->close();
}

$conn->close();
?> 