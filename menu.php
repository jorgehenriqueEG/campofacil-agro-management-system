<style>
    :root {
        --verde-escuro: #1b5e20; 
        --verde-claro: #8DB600; 
        --fundo-suave: #F4F7F6;
        --branco: #ffffff;
        --texto: #333333;
    }

    body {
        margin: 0;
        padding: 0;
        display: flex; 
        background-color: var(--fundo-suave);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    
    .sidebar {
        width: 260px;
        background-color: var(--verde-escuro);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        padding-top: 30px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 1000;
    }

    .logo-menu {
        color: var(--branco);
        text-align: center;
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 40px;
        letter-spacing: -1px;
    }

    .menu-item {
        color: var(--branco);
        text-decoration: none;
        padding: 15px 25px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: 0.3s;
    }

    .menu-item:hover {
        background-color: #2e7d32; 
    }

  
    .main-content {
        margin-left: 260px; 
        width: calc(100% - 260px); 
        padding: 40px;
        display: flex;
        flex-direction: column;
        align-items: center; 
    }

    
    .form-box, .table-box {
        background: var(--branco);
        width: 100%;
        max-width: 800px; 
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    input, select, button {
        font-size: 1rem;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
        width: 100%;
    }

    button.btn-agro {
        background-color: var(--verde-claro);
        color: var(--verde-escuro);
        border: none;
        cursor: pointer;
        font-weight: bold;
        transition: 0.3s;
    }

    button.btn-agro:hover {
        background-color: var(--verde-escuro);
        color: var(--branco);
        transform: translateY(-2px);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th { background-color: var(--fundo-suave); color: var(--verde-escuro); padding: 15px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #eee; }

    .btn-excluir {
        color: #e74c3c;
        text-decoration: none;
        font-weight: bold;
        font-size: 0.9rem;
    }
</style>

<div class="sidebar">
    <div class="logo-menu">CampoFácil</div>
    
    <a href="dashboard.php" class="menu-item"><i>📊</i> Dashboard</a>
    <a href="clientes.php" class="menu-item"><i>👥</i> Clientes</a>
    <a href="produtos.php" class="menu-item"><i>📦</i> Produtos</a>
    <a href="vendas.php" class="menu-item"><i>🛒</i> Vendas</a>
    <a href="alertas.php" class="menu-item"><i>🔔</i> Alertas</a>
    
    <div style="margin-top: auto; padding-bottom: 20px;">
        <a href="sair.php" class="menu-item" style="color: #ff8a80;"><i>🚪</i> Sair</a>
    </div>
</div>