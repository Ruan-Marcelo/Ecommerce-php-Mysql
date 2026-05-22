<?php
ob_start();
include 'registrar.php';
$output = ob_get_clean();

if (strpos($output, 'Registrar') !== false) {
    echo "Registrar page loads correctly - contains 'Registrar'<br>";
} else {
    echo "Registrar page may have issues<br>";
    echo "Output length: " . strlen($output) . "<br>";
}
?>