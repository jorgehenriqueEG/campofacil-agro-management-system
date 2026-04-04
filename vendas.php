<?php
include 'conexao.php'; include 'verificar_login.php';
$vid = $_SESSION['vendedor_id'];

if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $conn->query("DELETE FROM vendas WHERE id = $id AND vendedor_id = $vid");
    header("Location: vendas.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente_id'];
    $produto = $_POST['produto_id'];
    $qtd = $_POST['quantidade'];
    $data_v = $_POST['data_venda'];
    $dias = $_POST['dias_alerta'];

    $data_alerta = date('Y-m-d', strtotime($data_v . " + " . $dias . " days"));

    $sql = "INSERT INTO vendas (vendedor_id, cliente_id, produto_id, quantidade, data_venda, data_alerta) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiss", $vid, $cliente, $produto, $qtd, $data_v, $data_alerta);
    $stmt->execute();
}

$clientes = $conn->query("SELECT id, nome_fazenda FROM clientes WHERE vendedor_id = $vid");
$produtos = $conn->query("SELECT id, nome FROM produtos WHERE vendedor_id = $vid");
$ultimas = $conn->query("SELECT v.id, c.nome_fazenda, p.nome as pnome, v.quantidade, v.data_venda FROM vendas v JOIN clientes c ON v.cliente_id=c.id JOIN produtos p ON v.produto_id=p.id WHERE v.vendedor_id=$vid ORDER BY v.id DESC LIMIT 10");

include 'header.php'; include 'menu.php';
?>

<style>
    body { background: #0f172a !important; color:#ffffff; }
    .main-content { margin-left: 260px; padding: 40px; }
    .card-agro { background: #1e293b; border: 1px solid #334155; padding: 25px; border-radius: 12px; margin-bottom: 25px; }
    input, select { background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 8px; width: 100%; margin-bottom: 10px; }
    .btn-save { background: #4ade80; color: #0f172a; border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; cursor: pointer; }
</style>

<div class="main-content">
    <h2 style="color: #4ade80;">Registrar Saída / Venda</h2>
    <div class="card-agro">
        <form method="POST">
            <select name="cliente_id" required>
                <option value="">Selecione o Cliente</option>
                <?php while($c = $clientes->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= $c['nome_fazenda'] ?></option>
                <?php endwhile; ?>
            </select>
            <select name="produto_id" required>
                <option value="">Selecione o Produto</option>
                <?php while($p = $produtos->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
                <?php endwhile; ?>
            </select>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <input type="number" name="quantidade" placeholder="Quantidade" required>
                <input type="date" name="data_venda" value="<?= date('Y-m-d') ?>">
            </div>
            <input type="number" name="dias_alerta" placeholder="Alertar para recompra em quantos dias?" required>
            <button type="submit" class="btn-save">Gravar Venda</button>
        </form>
    </div>

    <div class="card-agro" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #334155;">
                <tr><th style="padding: 15px; text-align: left;">Cliente</th><th style="padding: 15px;">Item</th><th style="padding: 15px; text-align: center;">Ação</th></tr>
            </thead>
            <tbody>
                <?php while($u = $ultimas->fetch_assoc()): ?>
                <tr style="border-bottom: 1px solid #334155;">
                    <td style="padding: 15px;"><b><?= $u['nome_fazenda'] ?></b></td>
                    <td style="padding: 15px;"><?= $u['quantidade'] ?>un - <?= $u['pnome'] ?></td>
                    <td style="padding: 15px; text-align: center;">
                        <a href="?excluir=<?= $u['id'] ?>" style="color:#ef4444; text-decoration:none;" onclick="return confirm('Excluir registro?')">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>