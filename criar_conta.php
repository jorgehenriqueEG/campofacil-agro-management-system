<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($senha !== $confirma_senha) {
        echo "Senhas não conferem!";
        exit;
    }

    $hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO vendedores (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $hash);
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Erro: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>