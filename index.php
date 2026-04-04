<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

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
    <title>CampoFácil - Login</title>
    <style>
        :root {
            --verde-agro: #2D5A27;
            --verde-claro: #8DB600;
            --card-bg: rgba(30, 41, 59, 0.95);
            --text-main: #FFFFFF;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0f172a;
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('img/fundo-login.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-card {
            background: var(--card-bg);
            padding: 50px 40px;
            border-radius: 20px;
            border: 1px solid #334155;
            width: 100%;
            max-width: 400px;
            text-align: center;
            backdrop-filter: blur(8px);
        }

        h1 { color: var(--verde-claro); font-size: 2.2rem; margin-bottom: 5px; }
        p.subtitle { color: #cbd5e1; margin-bottom: 30px; font-size: 0.9rem; }

        .input-group { text-align: left; margin-bottom: 20px; }
        .input-group label { display: block; color: var(--verde-claro); font-size: 0.7rem; font-weight: bold; margin-bottom: 8px; text-transform: uppercase; }
        
        input { 
            width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #334155; 
            background: rgba(15, 23, 42, 0.7); color: white; outline: none; font-size: 1rem;
        }

        input:focus { border-color: var(--verde-claro); }

        .btn-green { 
            width: 100%; padding: 15px; background: var(--verde-agro); color: white; 
            border: none; border-radius: 10px; font-weight: bold; cursor: pointer; font-size: 1.1rem;
            transition: 0.3s;
        }

        .btn-green:hover { background: #244d20; transform: translateY(-2px); }

        .links { margin-top: 25px; font-size: 0.9rem; color: #cbd5e1; }
        .links a { color: var(--verde-claro); text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>CampoFácil</h1>
        <p class="subtitle">Tecnologia de ponta no campo</p>

        <form action="login_action.php" method="POST">
            <div class="input-group">
                <label>E-mail</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="input-group">
                <label>Senha</label>
                <input type="password" name="senha" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn-green">ENTRAR</button>
        </form>

        <div class="links">
            Não tem conta? <a href="register.php">Cadastre-se</a>
        </div>
    </div>
</body>
</html>