<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CampoFácil - Cadastro</title>
    <style>
        :root { --verde-agro: #2D5A27; --verde-claro: #8DB600; --card-bg: rgba(30, 41, 59, 0.95); }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { 
            height: 100vh; display: flex; justify-content: center; align-items: center; 
            background-color: #0f172a;
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.8)), url('img/fundo-login.jpg');
            background-size: cover; background-position: center;
        }
        .login-card { background: var(--card-bg); padding: 45px; border-radius: 20px; width: 100%; max-width: 400px; text-align: center; border: 1px solid #334155; }
        h1 { color: var(--verde-claro); margin-bottom: 25px; }
        .input-group { text-align: left; margin-bottom: 15px; }
        .input-group label { display: block; color: var(--verde-claro); font-size: 0.7rem; font-weight: bold; margin-bottom: 5px; }
        input { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #334155; background: rgba(15, 23, 42, 0.7); color: white; outline: none; }
        .btn-green { width: 100%; padding: 15px; background: var(--verde-agro); color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>Nova Conta</h1>
        <form action="cadastro_action.php" method="POST">
            <div class="input-group">
                <label>NOME COMPLETO</label>
                <input type="text" name="nome" required placeholder="Digite seu nome">
            </div>
            <div class="input-group">
                <label>E-MAIL</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="input-group">
                <label>SENHA</label>
                <input type="password" name="senha" required placeholder="Crie uma senha">
            </div>
            <button type="submit" class="btn-green">CRIAR CONTA</button>
        </form>
        <div style="margin-top: 20px;">
            <a href="index.php" style="color: #8DB600; text-decoration: none; font-size: 0.9rem;">← Voltar para Login</a>
        </div>
    </div>
</body>
</html>