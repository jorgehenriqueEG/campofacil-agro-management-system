<?php
include 'conexao.php';
include 'verificar_login.php';
include 'header.php';
include 'menu.php';

$vendedor_id = $_SESSION['vendedor_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome_produto'])) {
    $nome = $_POST['nome_produto'];
    $valor = $_POST['valor'];
    $ciclo = $_POST['ciclo'];

    $sql = "INSERT INTO produtos (vendedor_id, nome_produto, valor, ciclo_consumo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdi", $vendedor_id, $nome, $valor, $ciclo);
    $stmt->execute();
}
?>

<style>
    .main-content { margin-left: 260px; padding: 40px; background: #0f172a; min-height: 100vh; color: white; }
    .header-page { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    
    .form-container { background: #1e293b; padding: 25px; border-radius: 12px; border: 1px solid #334155; margin-bottom: 30px; }
    .form-container input { background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 8px; margin-right: 10px; }
    
    .grid-produtos { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
    .card-item { 
        background: #1e293b; 
        padding: 25px; 
        border-radius: 15px; 
        border-left: 5px solid #8DB600; 
        position: relative;
        transition: 0.3s;
    }
    .card-item:hover { transform: translateY(-5px); background: #26334d; }
    .card-item h3 { color: #8DB600; margin-bottom: 10px; text-transform: uppercase; font-size: 1rem; }
    .card-item .price { font-size: 1.5rem; font-weight: bold; margin-bottom: 5px; }
    .card-item .cycle { color: #94a3b8; font-size: 0.85rem; }
    
    .btn-del { color: #ef4444; text-decoration: none; font-size: 0.7rem; font-weight: bold; margin-top: 15px; display: inline-block; }
    .btn-add { background: #8DB600; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; cursor: pointer; }
</style>

<div class="main-content">
    <div class="header-page">
        <h2>Meus Produtos</h2>
    </div>

    <div class="form-container">
        <form method="POST" style="display: flex; flex-wrap: wrap; gap: 10px;">
            <input type="text" name="nome_produto" placeholder="Nome do Produto" required style="flex: 2;">
            <input type="number" step="0.01" name="valor" placeholder="Preço (R$)" required style="flex: 1;">
            <input type="number" name="ciclo" placeholder="Ciclo Consumo (dias)" required style="flex: 1;">
            <button type="submit" class="btn-add">CADASTRAR</button>
        </form>
    </div>

  <div class="grid-produtos">
    <?php
    $res = $conn->query("SELECT * FROM produtos WHERE vendedor_id = $vendedor_id ORDER BY id DESC");
    while ($p = $res->fetch_assoc()): 
        $nome_exibir = $p['nome_produto'] ?? $p['produto'] ?? 'Sem nome';
        $ciclo_exibir = $p['ciclo_consumo'] ?? $p['ciclo'] ?? '0';
    ?>
        <div class="card-item">
            <h3><?= htmlspecialchars($nome_exibir) ?></h3>
            <p class="price">R$ <?= number_format($p['valor'], 2, ',', '.') ?></p>
            <p class="cycle">⏳ Reposição a cada <?= $ciclo_exibir ?> dias</p>
            <hr style="margin: 15px 0; border: 0; border-top: 1px solid #334155;">
            <a href="excluir_produto.php?id=<?= $p['id'] ?>" class="btn-del" onclick="return confirm('Excluir este produto?')">REMOVER DO CATÁLOGO</a>
        </div>
    <?php endwhile; ?>
</div>
</div>