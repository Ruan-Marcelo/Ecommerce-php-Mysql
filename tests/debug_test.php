<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Starting debug...<br>";

try {
    require_once 'funcoes.php';
    echo "funcoes.php loaded<br>";

    session_start();
    echo "Session started<br>";

    // Test a simple function
    if (function_exists('obter_categorias')) {
        echo "obter_categorias exists<br>";
    } else {
        echo "obter_categorias NOT found<br>";
    }

} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
} catch (Throwable $t) {
    echo "Throwable: " . $t->getMessage() . "<br>";
    echo "Trace: " . $t->getTraceAsString() . "<br>";
}
?>