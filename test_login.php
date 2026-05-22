<?php
// Test login page
ob_start();
include 'login.php';
$output = ob_get_clean();

if (strpos($output, 'Entrar') !== false) {
    echo "Login page loads correctly - contains 'Entrar'<br>";
} else {
    echo "Login page may have issues<br>";
    echo "Output length: " . strlen($output) . "<br>";
}
?>