<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/conexao.php";

$erro = "";
$sucesso = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // VALIDAÇÕES

    if(strlen($nome) < 3){

        $erro = "Nome muito curto.";

    }

    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){

        $erro = "E-mail inválido.";

    }

    elseif(strlen($senha) < 6){

        $erro = "A senha deve ter pelo menos 6 caracteres.";

    }

    elseif($senha !== $confirmar_senha){

        $erro = "As senhas não coincidem.";

    }

    else{

        // VERIFICA SE EMAIL JÁ EXISTE

        $check = $conn->prepare("
        SELECT id
        FROM vendedores
        WHERE email = ?
        ");

        $check->bind_param("s", $email);

        $check->execute();

        $res = $check->get_result();

        if($res->num_rows > 0){

            $erro = "Este e-mail já está cadastrado.";

        }else{

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "
            INSERT INTO vendedores
            (
                nome,
                email,
                senha
            )

            VALUES

            (?, ?, ?)
            ";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "sss",
                $nome,
                $email,
                $senha_hash
            );

            if($stmt->execute()){

                $sucesso = "Conta criada com sucesso!";

            }else{

                $erro = "Erro ao criar conta.";

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CampoFácil - Cadastro</title>

<style>

:root{
    --verde-agro:#2D5A27;
    --verde-claro:#8DB600;
    --card-bg:rgba(30,41,59,0.95);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{

    height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background-color:#0f172a;

    background-image:
    linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)),
    url('../assets/imagens/fundo.jpg');

    background-size:cover;
    background-position:center;
}

.login-card{

    background:var(--card-bg);

    padding:45px;

    border-radius:20px;

    width:100%;
    max-width:420px;

    text-align:center;

    border:1px solid #334155;

    backdrop-filter:blur(6px);
}

h1{
    color:var(--verde-claro);
    margin-bottom:25px;
}

.input-group{
    text-align:left;
    margin-bottom:15px;
}

.input-group label{

    display:block;

    color:var(--verde-claro);

    font-size:0.7rem;

    font-weight:bold;

    margin-bottom:5px;
}

input{

    width:100%;

    padding:12px;

    border-radius:10px;

    border:1px solid #334155;

    background:rgba(15,23,42,0.7);

    color:white;

    outline:none;
}

input:focus{
    border-color:var(--verde-claro);
}

.btn-green{

    width:100%;

    padding:15px;

    background:var(--verde-agro);

    color:white;

    border:none;

    border-radius:10px;

    font-weight:bold;

    cursor:pointer;

    transition:0.3s;
}

.btn-green:hover{
    background:#244d20;
}

.alerta{

    padding:12px;

    border-radius:8px;

    margin-bottom:20px;

    font-size:0.9rem;

    font-weight:bold;
}

.erro{
    background:#7f1d1d;
    color:#fecaca;
}

.sucesso{
    background:#14532d;
    color:#bbf7d0;
}

.link-login{
    margin-top:20px;
}

.link-login a{
    color:#8DB600;
    text-decoration:none;
    font-size:0.9rem;
}

</style>

</head>

<body>

<div class="login-card">

    <h1>Nova Conta</h1>

    <?php if($erro): ?>

        <div class="alerta erro">
            <?= $erro ?>
        </div>

    <?php endif; ?>

    <?php if($sucesso): ?>

        <div class="alerta sucesso">
            <?= $sucesso ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="input-group">

            <label>NOME COMPLETO</label>

            <input
            type="text"
            name="nome"
            required
            placeholder="Digite seu nome"
            >

        </div>

        <div class="input-group">

            <label>E-MAIL</label>

            <input
            type="email"
            name="email"
            required
            placeholder="seu@email.com"
            >

        </div>

        <div class="input-group">

            <label>SENHA</label>

            <input
            type="password"
            name="senha"
            required
            placeholder="Crie uma senha"
            >

        </div>

        <div class="input-group">

            <label>CONFIRMAR SENHA</label>

            <input
            type="password"
            name="confirmar_senha"
            required
            placeholder="Repita a senha"
            >

        </div>

        <button type="submit" class="btn-green">

            CRIAR CONTA

        </button>

    </form>

    <div class="link-login">

        <a href="login.php">
            ← Voltar para Login
        </a>

    </div>

</div>

</body>
</html>