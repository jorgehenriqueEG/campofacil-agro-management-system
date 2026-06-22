<?php

session_start();

include "../includes/conexao.php";

if(isset($_SESSION['vendedor_id'])){

    header("Location: ../pages/dashboard.php");
    exit;

}

if(isset($_POST['email'])){

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM vendedores WHERE email=?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if(password_verify($senha, $user['senha'])){

            $_SESSION['vendedor_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];

            header("Location: ../pages/dashboard.php");
            exit;

        }else{

            $erro = "Senha inválida";

        }

    }else{

        $erro = "Usuário não encontrado";

    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CampoFácil - Login</title>

<style>

:root{
    --bg-dark:#0f172a;
    --card-dark:#1e293b;
    --border-dark:#334155;
    --accent-green:#4ade80;
    --text-main:#f8fafc;
}

*{
    box-sizing:border-box;
}

body{

    margin:0;
    padding:0;

    font-family:'Segoe UI',sans-serif;

    color:var(--text-main);

    display:flex;
    justify-content:center;
    align-items:center;

    min-height:100vh;

    background:
    linear-gradient(rgba(15,23,42,0.75), rgba(15,23,42,0.75)),
    url('../assets/imagens/fundo.jpg');

    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
}

.login-card{

    width:100%;
    max-width:420px;

    background:rgba(30,41,59,0.88);

    backdrop-filter:blur(6px);

    border:1px solid var(--border-dark);

    padding:40px;

    border-radius:16px;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.35);

    text-align:center;
}

.logo-area h1{
    color:var(--accent-green);
    font-size:2.4rem;
    margin-bottom:5px;
}

.logo-area p{
    color:#94a3b8;
    font-size:0.95rem;
    margin-bottom:30px;
}

.input-group{
    text-align:left;
    margin-bottom:18px;
}

.input-group label{
    display:block;
    font-size:0.8rem;
    font-weight:bold;
    color:var(--accent-green);
    margin-bottom:6px;
    text-transform:uppercase;
}

.input-group input{

    width:100%;

    padding:14px;

    border:1px solid var(--border-dark);

    background-color:#0f172a;

    color:white;

    border-radius:10px;

    font-size:1rem;
}

.input-group input:focus{
    outline:none;
    border-color:var(--accent-green);
}

.btn-green{

    width:100%;

    padding:15px;

    background-color:var(--accent-green);

    color:#0f172a;

    border:none;

    border-radius:10px;

    font-size:1rem;

    font-weight:bold;

    cursor:pointer;

    transition:0.3s;
}

.btn-green:hover{
    transform:translateY(-2px);
    background-color:#22c55e;
}

.link-cadastro{
    margin-top:22px;
    font-size:0.95rem;
    color:#94a3b8;
}

.link-cadastro a{
    color:var(--accent-green);
    text-decoration:none;
    font-weight:bold;
}

.erro{
    background:#7f1d1d;
    color:#fecaca;
    padding:12px;
    border-radius:8px;
    margin-bottom:18px;
    font-size:0.9rem;
}

</style>

</head>

<body>

<div class="login-card">

    <div class="logo-area">

        <h1>CampoFácil</h1>

        <p>A tecnologia no seu campo</p>

    </div>

    <?php if(isset($erro)): ?>

        <div class="erro">
            <?= $erro ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="input-group">

            <label>E-mail</label>

            <input
                type="email"
                name="email"
                required
                placeholder="exemplo@email.com"
            >

        </div>

        <div class="input-group">

            <label>Senha</label>

            <input
                type="password"
                name="senha"
                required
                placeholder="Sua senha"
            >

        </div>

        <button type="submit" class="btn-green">
            ENTRAR NO SISTEMA
        </button>

    </form>

    <div class="link-cadastro">

        Não tem conta?

        <a href="criar_conta.php">
            Cadastre-se
        </a>

    </div>

</div>

</body>
</html>