<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['vendedor_id'])) {
    header("Location: index.php");
    exit();
}function getVendedorId() {
    return $_SESSION['vendedor_id'];
}
?>