<?php
include 'conexao.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $vendedor_id = $_SESSION['vendedor_id'];

    $sql = "DELETE FROM produtos WHERE id = ? AND vendedor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $vendedor_id);
    $stmt->execute();
}

header("Location: produtos.php");
exit();
?>