<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/conexao.php";
include "../includes/verificar_login.php";

$vid = $_SESSION['vendedor_id'];

if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];

    $stmt = $conn->prepare("
        DELETE FROM vendas
        WHERE id = ?
        AND vendedor_id = ?
    ");

    $stmt->bind_param("ii", $id, $vid);
    $stmt->execute();

    header("Location: vendas.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $cliente_id = (int)$_POST['cliente_id'];
    $produto_id = (int)$_POST['produto_id'];
    $quantidade = (int)$_POST['quantidade'];
    $data_venda = $_POST['data_venda'];

    if($cliente_id <= 0){

        $erro = "Cliente inválido.";

    }elseif($produto_id <= 0){

        $erro = "Produto inválido.";

    }elseif($quantidade <= 0){

        $erro = "Quantidade inválida.";

    }else{

        // AJUSTE: Buscando também consumo_medio e prazo_seguranca para o cálculo exato
        $stmt_prod = $conn->prepare("
            SELECT dias_consumo, estoque, consumo_medio, prazo_seguranca
            FROM produtos
            WHERE id = ?
            AND vendedor_id = ?
        ");

        $stmt_prod->bind_param("ii", $produto_id, $vid);
        $stmt_prod->execute();

        $produto = $stmt_prod->get_result()->fetch_assoc();

        if(!$produto){

            $erro = "Produto não encontrado.";

        }else{

            $dias_consumo = (int)$produto['dias_consumo'];
            $estoque_atual = (int)$produto['estoque'];
            $consumo_medio = (float)$produto['consumo_medio'];
            $prazo_seguranca = (int)$produto['prazo_seguranca'];

            if($quantidade > $estoque_atual){

                $erro = "Estoque insuficiente.";

            }else{

                if($consumo_medio > 0){
                    $dias_duracao = round($quantidade / $consumo_medio);
                }else{
                    $dias_duracao = ($dias_consumo > 0) ? $dias_consumo : 30;
                }

                if($dias_duracao < 1){
                    $dias_duracao = 1;
                }

                $data_recompra = date(
                    'Y-m-d',
                    strtotime($data_venda . " +$dias_duracao days")
                );

                $antecedencia = ($prazo_seguranca > 0) ? $prazo_seguranca : 3;

                $data_alerta = date(
                    'Y-m-d',
                    strtotime($data_recompra . " -$antecedencia days")
                );

                if(strtotime($data_alerta) < strtotime($data_venda)){
                    $data_alerta = $data_venda;
                }

                $sql = "
                INSERT INTO vendas
                (
                    vendedor_id,
                    cliente_id,
                    produto_id,
                    quantidade,
                    data_venda,
                    data_recompra,
                    data_alerta
                )

                VALUES

                (?, ?, ?, ?, ?, ?, ?)
                ";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param(
                    "iiiisss",
                    $vid,
                    $cliente_id,
                    $produto_id,
                    $quantidade,
                    $data_venda,
                    $data_recompra,
                    $data_alerta
                );

                if($stmt->execute()){

                    $novo_estoque = $estoque_atual - $quantidade;

                    $stmt_update = $conn->prepare("
                        UPDATE produtos
                        SET estoque = ?
                        WHERE id = ?
                        AND vendedor_id = ?
                    ");

                    $stmt_update->bind_param(
                        "iii",
                        $novo_estoque,
                        $produto_id,
                        $vid
                    );

                    $stmt_update->execute();

                    $sucesso = "Venda registrada com sucesso.";

                }else{

                    $erro = "Erro ao registrar venda.";
                }
            }
        }
    }
}

$stmt_clientes = $conn->prepare("
    SELECT id, nome_fazenda
    FROM clientes
    WHERE vendedor_id = ?
");

$stmt_clientes->bind_param("i", $vid);
$stmt_clientes->execute();

$clientes = $stmt_clientes->get_result();

$stmt_produtos = $conn->prepare("
    SELECT id, nome
    FROM produtos
    WHERE vendedor_id = ?
");

$stmt_produtos->bind_param("i", $vid);
$stmt_produtos->execute();

$produtos = $stmt_produtos->get_result();

$stmt_vendas = $conn->prepare("
SELECT
v.id,
c.nome_fazenda,
p.nome AS produto,
v.quantidade,
v.data_venda,
v.data_recompra,
v.data_alerta

FROM vendas v

JOIN clientes c
ON v.cliente_id = c.id

JOIN produtos p
ON v.produto_id = p.id

WHERE v.vendedor_id = ?

ORDER BY v.id DESC
");

$stmt_vendas->bind_param("i", $vid);
$stmt_vendas->execute();

$vendas = $stmt_vendas->get_result();

include "../includes/menu.php";

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Vendas - CampoFácil</title>

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

.card{
    background:#1e293b;
    border:1px solid #334155;
    padding:30px;
    border-radius:12px;
    margin-bottom:30px;
}

h2{
    color:#4ade80;
}

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

input,
select{
    background:#0f172a;
    border:1px solid #334155;
    color:white;
    padding:12px;
    border-radius:8px;
    width:100%;
}

button{
    background:#4ade80;
    color:#0f172a;
    border:none;
    padding:15px;
    border-radius:8px;
    width:100%;
    margin-top:20px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#334155;
    padding:15px;
    text-align:left;
}

td{
    padding:15px;
    border-bottom:1px solid #334155;
}

.delete{
    color:#ef4444;
    text-decoration:none;
}

.alerta{
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
    font-weight:bold;
}

.erro{
    background:#7f1d1d;
    color:#fecaca;
}

.sucesso{
    background:#14532d;
    color:#bbf7d0;
}

</style>

</head>

<body>

<div class="main-content">

<h2> Registrar Venda</h2>

<?php if(isset($erro)): ?>

<div class="alerta erro">
    <?= $erro ?>
</div>

<?php endif; ?>

<?php if(isset($sucesso)): ?>

<div class="alerta sucesso">
    <?= $sucesso ?>
</div>

<?php endif; ?>

<div class="card">

<form method="POST">

<div class="grid">

<select name="cliente_id" required>

<option value="">Selecione o Cliente</option>

<?php while($c = $clientes->fetch_assoc()): ?>

<option value="<?= $c['id'] ?>">
<?= htmlspecialchars($c['nome_fazenda']) ?>
</option>

<?php endwhile; ?>

</select>

<select name="produto_id" required>

<option value="">Selecione o Produto</option>

<?php while($p = $produtos->fetch_assoc()): ?>

<option value="<?= $p['id'] ?>">
<?= htmlspecialchars($p['nome']) ?>
</option>

<?php endwhile; ?>

</select>

<input
type="number"
name="quantidade"
placeholder="Quantidade"
required
>

<input
type="date"
name="data_venda"
value="<?= date('Y-m-d') ?>"
required
>

</div>

<button type="submit">
Registrar Venda
</button>

</form>

</div>

<div class="card">

<table>

<thead>

<tr>
<th>Cliente</th>
<th>Produto</th>
<th>Qtd</th>
<th>Venda</th>
<th>Recompra</th>
<th>Alerta</th>
<th>Ação</th>
</tr>

</thead>

<tbody>

<?php while($v = $vendas->fetch_assoc()): ?>

<tr>

<td><?= htmlspecialchars($v['nome_fazenda']) ?></td>

<td><?= htmlspecialchars($v['produto']) ?></td>

<td><?= $v['quantidade'] ?></td>

<td>
<?= date('d/m/Y', strtotime($v['data_venda'])) ?>
</td>

<td style="color:#facc15;">
<?= date('d/m/Y', strtotime($v['data_recompra'])) ?>
</td>

<td style="color:#4ade80;">
<?= date('d/m/Y', strtotime($v['data_alerta'])) ?>
</td>

<td>

<a
href="?excluir=<?= $v['id'] ?>"
class="delete"
onclick="return confirm('Excluir venda?')"
>
🗑️
</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>