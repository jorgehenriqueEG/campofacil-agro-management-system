<?php
include 'conexao.php'; include 'verificar_login.php';
$vid = $_SESSION['vendedor_id'];

if (isset($_GET['tabela']) && isset($_GET['id'])) {
    $tab = $_GET['tabela']; $id = $_GET['id'];
    //segurança para evitar exclusão acidental
    $sql = "DELETE FROM $tab WHERE id = $id AND vendedor_id = $vid";
    $conn->query($sql);
    header("Location: $tab.php");
}
?>