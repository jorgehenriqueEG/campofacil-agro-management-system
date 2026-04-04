<?php
include 'conexao.php'; 
include 'verificar_login.php';
$vid = $_SESSION['vendedor_id'];

$total_clientes = $conn->query("SELECT COUNT(*) as t FROM clientes WHERE vendedor_id = $vid")->fetch_assoc()['t'];
$total_vendas = $conn->query("SELECT SUM(quantidade) as t FROM vendas WHERE vendedor_id = $vid")->fetch_assoc()['t'] ?? 0;
$total_produtos = $conn->query("SELECT COUNT(*) as t FROM produtos WHERE vendedor_id = $vid")->fetch_assoc()['t'];

//  HISTÓRICO DETALHADO (Colunas Reais: nome_fazenda, cidade, uf)
$ultimas = $conn->query("SELECT 
    c.nome_fazenda, 
    c.cidade, 
    c.uf, 
    p.nome as prod, 
    v.quantidade 
    FROM vendas v 
    JOIN clientes c ON v.cliente_id = c.id 
    JOIN produtos p ON v.produto_id = p.id 
    WHERE v.vendedor_id = $vid 
    ORDER BY v.id DESC LIMIT 8");

include 'menu.php';
?>

<style>
    body { background: #0f172a !important; color: #f8fafc !important; }
    .main-content { background: #0f172a; min-height: 100vh; padding: 40px; margin-left: 260px; }
    .card-stats { background: #1e293b; border: 1px solid #334155; padding: 25px; border-radius: 12px; text-align: center; flex: 1; border-bottom: 4px solid #4ade80; }
    .table-box { background: #1e293b; border: 1px solid #334155; border-radius: 12px; width: 100%; max-width: 1100px; margin-top: 25px; overflow: hidden; }
    h2, h3 { color: #4ade80 !important; font-weight: 600; }
    table { width: 100%; border-collapse: collapse; }
    th { color: #94a3b8; text-align: left; padding: 15px; font-size: 0.75rem; text-transform: uppercase; border-bottom: 1px solid #334155; }
    td { padding: 15px; border-bottom: 1px solid #334155; font-size: 0.9rem; color: #cbd5e1; }
    .tag-location { color: #818cf8; font-weight: 500; }
    .tag-qtd { background: #064e3b; color: #4ade80; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
</style>

<div class="main-content">
    <div style="width: 100%; max-width: 1100px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; letter-spacing: 1px;">PAINEL <span style="color: white !important;">GERAL</span></h2>
            <p style="color: #64748b; margin: 5px 0 0 0;">Dados em tempo real do CampoFácil</p>
        </div>
    </div>

    <div style="display: flex; gap: 20px; width: 100%; max-width: 1100px;">
        <div class="card-stats">
            <div style="font-size: 2.2rem; font-weight: bold;"><?= $total_clientes ?></div>
            <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase;">Parceiros Atendidos</div>
        </div>
        <div class="card-stats">
            <div style="font-size: 2.2rem; font-weight: bold; color: #4ade80;"><?= $total_vendas ?></div>
            <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase;">Volume Total Saídas</div>
        </div>
        <div class="card-stats">
            <div style="font-size: 2.2rem; font-weight: bold; color: #fbbf24;"><?= $total_produtos ?></div>
            <div style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase;">Produtos Ativos</div>
        </div>
    </div>

    <div class="table-box">
        <div style="padding: 20px; border-bottom: 1px solid #334155;">
            <h3 style="margin: 0; font-size: 1.1rem;">📊 Últimas Atividades</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Fazenda / Propriedade</th>
                    <th>Localização</th>
                    <th>Insumo</th>
                    <th style="text-align: center;">Qtd</th>
                </tr>
            </thead>
            <tbody>
                <?php while($u = $ultimas->fetch_assoc()): ?>
                <tr>
                    <td><b style="color: #f8fafc;"><?= htmlspecialchars($u['nome_fazenda']) ?></b></td>
                    <td>
                        <span class="tag-location">📍 <?= htmlspecialchars($u['cidade']) ?> - <?= strtoupper($u['uf']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($u['prod']) ?></td>
                    <td style="text-align: center;">
                        <span class="tag-qtd"><?= $u['quantidade'] ?></span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>