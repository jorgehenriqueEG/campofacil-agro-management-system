<?php
include 'conexao.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = $_POST['senha'];

    $result = $conn->query("SELECT * FROM vendedores WHERE email = '$email'");

    if ($result->num_rows > 0) {
        $vendedor = $result->fetch_assoc();

        if (password_verify($senha, $vendedor['senha'])) {
            
            $_SESSION['vendedor_id'] = $vendedor['id'];
            $_SESSION['vendedor_nome'] = $vendedor['nome'];

            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Senha incorreta!'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('E-mail não encontrado!'); window.location.href='index.php';</script>";
    }
}
?>