<?php
// Simple test to see if index.php loads without errors
ob_start();
include 'index.php';
$output = ob_get_clean();

if (strpos($output, 'LUPIÈRE') !== false) {
    echo "Index page loads correctly - contains LUPIÈRE<br>";
} else {
    echo "Index page may have issues<br>";
    echo "Output length: " . strlen($output) . "<br>";
    echo "First 200 chars: " . substr($output, 0, 200) . "<br>";
}
?>