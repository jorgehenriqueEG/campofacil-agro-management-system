<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'conexao.php'; include 'verificar_login.php';
$vid = $_SESSION['vendedor_id'];
include 'header.php'; include 'menu.php';
?>
<div class="main-content" style="margin-left: 260px; padding: 40px; background:#0f172a; min-height: 100vh;">
    <h2 style="color: #4ade80;">Próximas Recompras <small style="color:#94a3b8; font-size:0.9rem;">(Próximos 10 dias)</small></h2>
    
    <div style="display: grid; gap: 15px;">
        <?php
        $sql = "SELECT c.nome_fazenda, c.cidade, c.telefone, p.nome as pnome, v.data_alerta 
                FROM vendas v JOIN clientes c ON v.cliente_id = c.id JOIN produtos p ON v.produto_id = p.id
                WHERE v.vendedor_id = ? AND DATEDIFF(v.data_alerta, CURDATE()) <= 10 ORDER BY v.data_alerta ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vid);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($r = $res->fetch_assoc()): 
            $fone = preg_replace('/\D/', '', $r['telefone']);
            $msg = urlencode("Olá " . $r['nome_fazenda'] . "! Notamos que o prazo para reposição do item " . $r['pnome'] . " está chegando. Podemos agendar o envio?");
        ?>
        <div style="background: #1e293b; padding: 20px; border-radius: 12px; border: 1px solid #334155; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <b style="color:white; font-size:1.2rem;"><?= $r['nome_fazenda'] ?></b><br>
                <span style="color:#94a3b8;">📍 <?= $r['cidade'] ?> | Produto: <?= $r['pnome'] ?></span>
            </div>
            <div style="text-align: right;">
                <div style="color:#4ade80; font-weight:bold; font-size: 1.1rem; margin-bottom:8px;">📅 <?= date('d/m/Y', strtotime($r['data_alerta'])) ?></div>
                <a href="https://api.whatsapp.com/send?phone=55<?= $fone ?>&text=<?= $msg ?>" target="_blank" style="background:#25d366; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:bold; font-size:0.9rem;">WHATSAPP</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>