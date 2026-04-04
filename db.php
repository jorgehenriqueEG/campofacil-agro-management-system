<?php
$host = "localhost";
$user = "root";
$pass = ""; //senha aqui
$db   = "campofacil";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
function verificarLogin() {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php"); 
        exit();
    }
    return $_SESSION['usuario_id'];
}
?>