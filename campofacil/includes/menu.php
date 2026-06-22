<?php
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>

<style>

:root{
    --verde-escuro:#1b5e20;
    --verde-claro:#8DB600;
    --fundo:#0f172a;
    --card:#1e293b;
    --texto:#f8fafc;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial;
    background:var(--fundo);
    color:var(--texto);
}

.sidebar{
    width:260px;
    height:100vh;
    background:var(--verde-escuro);
    position:fixed;
    left:0;
    top:0;
    display:flex;
    flex-direction:column;
    padding-top:30px;
    z-index:999;
}

.logo-menu{
    color:white;
    font-size:2rem;
    font-weight:bold;
    text-align:center;
    margin-bottom:40px;
}

.menu-item{
    color:white;
    text-decoration:none;
    padding:15px 25px;
    display:flex;
    align-items:center;
    gap:12px;
    transition:0.3s;
    font-size:1rem;
}

.menu-item:hover,
.menu-item.active{
    background:#2e7d32;
}

.main-content{
    margin-left:260px;
    padding:40px;
    min-height:100vh;
}

@media(max-width:768px){

    .sidebar{
        width:70px;
    }

    .logo-menu,
    .menu-item span{
        display:none;
    }

    .main-content{
        margin-left:70px;
    }
}

</style>

<div class="sidebar">

    <div class="logo-menu">
        🌾 CampoFácil
    </div>

    <a href="../pages/dashboard.php"
    class="menu-item <?= $pagina_atual == 'dashboard.php' ? 'active' : '' ?>">
        📊 <span>Dashboard</span>
    </a>

    <a href="../pages/clientes.php"
    class="menu-item <?= $pagina_atual == 'clientes.php' ? 'active' : '' ?>">
        👥 <span>Clientes</span>
    </a>

    <a href="../pages/produtos.php"
    class="menu-item <?= $pagina_atual == 'produtos.php' ? 'active' : '' ?>">
        📦 <span>Produtos</span>
    </a>

    <a href="../pages/vendas.php"
    class="menu-item <?= $pagina_atual == 'vendas.php' ? 'active' : '' ?>">
        🛒 <span>Vendas</span>
    </a>

    <a href="../pages/alertas.php"
    class="menu-item <?= $pagina_atual == 'alertas.php' ? 'active' : '' ?>">
        🔔 <span>Alertas</span>
    </a>

    <div style="margin-top:auto;padding-bottom:20px;">

        <a href="../auth/logout.php"
        class="menu-item"
        style="color:#ff8a80;">
            🚪 <span>Sair</span>
        </a>

    </div>

</div>