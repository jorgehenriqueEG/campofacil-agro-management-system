<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
// Redireciona se já estiver logado
if (isset($_SESSION['vendedor_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampoFácil - Login Premium</title>
    <style>
        :root {
            --bg-dark: #0f172a;
            --card-dark: #1e293b;
            --border-dark: #334155;
            --accent-green: #2ecc71;
            --text-main: #f8fafc;
        }

        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-card {
            background-color: var(--card-dark);
            border: 1px solid var(--border-dark);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo-area h1 {
            color: var(--accent-green);
            font-size: 2.2rem;
            margin-bottom: 5px;
        }

        .logo-area p {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: bold;
            color: var(--accent-green);
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-dark);
            background-color: var(--bg-dark);
            color: white;
            border-radius: 8px;
            font-size: 1rem;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--accent-green);
        }

        .btn-green {
            width: 100%;
            padding: 14px;
            background-color: var(--accent-green);
            color: #0f172a;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-green:hover {
            transform: translateY(-2px);
            background-color: #27ae60;
        }

        .link-cadastro {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .link-cadastro a {
            color: var(--accent-green);
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-area">
            <h1>CampoFácil</h1>
            <p>A tecnologia no seu campo</p>
        </div>
        
        <form action="login_action.php" method="POST">
            <div class="input-group">
                <label>E-mail</label>
                <input type="email" name="email" required placeholder="exemplo@email.com">
            </div>
            
            <div class="input-group">
                <label>Senha</label>
                <input type="password" name="senha" required placeholder="Sua senha secreta">
            </div>
            
            <button type="submit" class="btn-green">ENTRAR NO SISTEMA</button>
        </form>
        
        <div class="link-cadastro">
            Não tem conta? <a href="cadastro.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>