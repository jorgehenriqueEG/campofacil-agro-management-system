<?php
include "conexao.php";

if(isset($_POST['nome'])){

$nome=$_POST['nome'];
$email=$_POST['email'];
$senha=password_hash($_POST['senha'], PASSWORD_DEFAULT);

mysqli_query($conn,"INSERT INTO vendedores(nome,email,senha)
VALUES('$nome','$email','$senha')");

header("Location: login.php");
exit;
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<link rel="stylesheet" href="/campofacilv2/css/style.css">

</head>

<body class="login-body">

<div class="login-card">

<h1>Criar Conta</h1>

<form method="POST">

<input name="nome" placeholder="Nome completo" required>

<input name="email" placeholder="Email" required>

<input type="password" name="senha" placeholder="Senha" required>

<button>Criar Conta</button>

</form>

</div>

</body>
</html>