<?php 

$sName = "localhost";
$uName = "root";
$pass = "";
$db_name = "lupiere";

try {

    $pdo = new PDO(
        "mysql:host=$sName;port=3307;dbname=$db_name;charset=utf8mb4",
        $uName,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    die("Erro de conexão: " . $e->getMessage());

}
?>