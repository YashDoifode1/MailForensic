<?php
// Database connection
$conn = new mysqli("sql101.infinityfree.com	
", "if0_37188433", "9nzZKwZmCC", "if0_37188433_track");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get visitor details
$ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Access';

// Parse user-agent for browser & OS
function getBrowserOS($user_agent) {
    $os_array = ['Windows', 'Macintosh', 'Linux', 'Ubuntu', 'iPhone', 'Android'];
    $browser_array = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'IE'];

    $os = 'Unknown OS';
    $browser = 'Unknown Browser';

    foreach ($os_array as $o) {
        if (strpos($user_agent, $o) !== false) {
            $os = $o;
            break;
        }
    }

    foreach ($browser_array as $b) {
        if (strpos($user_agent, $b) !== false) {
            $browser = $b;
            break;
        }
    }

    return [$browser, $os];
}

list($browser, $os) = getBrowserOS($user_agent);

// Detect mobile or desktop
$device = preg_match('/Mobile|Android|iPhone/', $user_agent) ? 'Mobile' : 'Desktop';

// Insert into database
$stmt = $conn->prepare("INSERT INTO tracking_logs (ip_address, user_agent, referer, browser, os, device) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $ip, $user_agent, $referer, $browser, $os, $device);
$stmt->execute();
$stmt->close();
$conn->close();

// Send 1x1 pixel transparent image
header("Content-Type: image/gif");
echo base64_decode("R0lGODlhAQABAPAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");
exit;
?>
