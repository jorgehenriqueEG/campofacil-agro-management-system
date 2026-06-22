<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/conexao.php";
include "../includes/verificar_login.php";

$vid = $_SESSION['vendedor_id'];

$sql = "
SELECT
c.nome_fazenda,
c.telefone,
c.cidade,
p.nome AS produto,
v.data_alerta,
v.data_venda,

DATEDIFF(v.data_alerta, CURDATE()) AS dias_restantes

FROM vendas v

JOIN clientes c
ON v.cliente_id = c.id

JOIN produtos p
ON v.produto_id = p.id

WHERE
v.vendedor_id = ?
AND v.data_alerta <= CURDATE()
ORDER BY v.data_alerta ASC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $vid);

$stmt->execute();

$res = $stmt->get_result();

include "../includes/menu.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Alertas - CampoFácil</title>

<style>

body{
    margin:0;
    background:#0f172a;
    color:white;
    font-family:Arial;
}

.main-content{
    margin-left:260px;
    padding:40px;
}

.alert-card{
    background:#1e293b;
    border:1px solid #334155;
    padding:25px;
    border-radius:12px;
    margin-bottom:20px;
}

.alert-card h3{
    margin-top:0;
    color:#4ade80;
}

.alert-date{
    color:#facc15;
    font-weight:bold;
}

.btn-whats{
    display:inline-block;
    margin-top:15px;
    background:#25d366;
    color:white;
    text-decoration:none;
    padding:12px 18px;
    border-radius:8px;
    font-weight:bold;
}

.btn-whats:hover{
    opacity:0.9;
}

.empty{
    background:#1e293b;
    padding:30px;
    border-radius:12px;
    border:1px solid #334155;
    color:#94a3b8;
}

.status{
    margin-top:12px;
    padding:10px;
    border-radius:8px;
    font-weight:bold;
}

.urgente{
    background:#7f1d1d;
    color:#fecaca;
}

.atencao{
    background:#78350f;
    color:#fde68a;
}

.normal{
    background:#14532d;
    color:#bbf7d0;
}

</style>

</head>

<body>

<div class="main-content">

<h2 style="color:#4ade80;">
🔔 Alertas de Recompra
</h2>

<?php if($res->num_rows > 0): ?>

<?php while($r = $res->fetch_assoc()): ?>

<?php

$fone = preg_replace('/\D/', '', $r['telefone']);

$dias = (int)$r['dias_restantes'];

if($dias <= 0){

    $status =  " Cliente provavelmente já precisa de reposição.";
    $classe = "urgente";

}elseif($dias <= 3){

    $status = " Entrar em contato imediatamente.";
    $classe = "urgente";

}elseif($dias <= 7){

    $status = " Recomendado iniciar contato.";
    $classe = "atencao";

}else{

    $status = " Alerta preventivo.";
    $classe = "normal";
}

$msg = urlencode(
"Olá! Tudo bem? 👋\n\n"
. "Notamos que pode estar próximo da reposição do produto "
. $r['produto']
. ".\n\nDeseja realizar um novo pedido?"
);

?>

<div class="alert-card">

<h3>
<?= htmlspecialchars($r['nome_fazenda']) ?>
</h3>

<p>
 <?= htmlspecialchars($r['cidade']) ?>
</p>

<p>
 Produto:
<b><?= htmlspecialchars($r['produto']) ?></b>
</p>

<p>
 Venda:
<?= date('d/m/Y', strtotime($r['data_venda'])) ?>
</p>

<p class="alert-date">
alerta:
<?= date('d/m/Y', strtotime($r['data_alerta'])) ?>
</p>

<div class="status <?= $classe ?>">

<?= $status ?>

<?php if($dias > 0): ?>

<br><br>
Faltam <?= $dias ?> dia(s) para o alerta.

<?php endif; ?>

</div>

<a
class="btn-whats"
target="_blank"
href="https://wa.me/55<?= $fone ?>?text=<?= $msg ?>"
>
Abrir WhatsApp
</a>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="empty">

Nenhum alerta disponível no momento.

</div>

<?php endif; ?>

</div>

</body>
</html>