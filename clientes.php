<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'conexao.php'; include 'verificar_login.php';
$vid = $_SESSION['vendedor_id'];

if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $conn->query("DELETE FROM clientes WHERE id = $id AND vendedor_id = $vid");
    header("Location: clientes.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO clientes (vendedor_id, nome_fazenda, cidade, uf, telefone, tipo_gado) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $vid, $_POST['nome_fazenda'], $_POST['cidade'], $_POST['uf'], $_POST['telefone'], $_POST['tipo_gado']);
    $stmt->execute();
}

$clientes = $conn->query("SELECT * FROM clientes WHERE vendedor_id=$vid ORDER BY id DESC");
include 'header.php'; include 'menu.php';
?>
<style>
    body { background: #0f172a !important; color: #aa9595; }
    .main-content { margin-left: 260px; padding: 40px; }
    .card-dark { background: #1e293b; border: 1px solid #334155; padding: 30px; border-radius: 12px; margin-bottom: 30px; }
    input, select { background: #0f172a; border: 1px solid #334155; color: white; padding: 12px; border-radius: 8px; width: 100%; margin-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #334155; padding: 15px; text-align: left; color: #94a3b8; font-size: 0.8rem; }
    td { padding: 15px; border-bottom: 1px solid #334155; }
    .btn-add { background: #4ade80; color: #0f172a; border: none; padding: 15px; border-radius: 8px; font-weight: bold; width: 100%; cursor: pointer; }
</style>
<div class="main-content">
    <h2 style="color: #4ade80;">Gerenciar Clientes</h2>
    <div class="card-dark">
        <form method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <input type="text" name="nome_fazenda" placeholder="Nome da Fazenda" required>
                <input type="text" name="cidade" placeholder="Cidade" required>
                <input type="text" name="uf" placeholder="UF (Ex: MG)" maxlength="2">
                <input type="text" name="telefone" placeholder="WhatsApp (Somente números)">
                <select name="tipo_gado" style="grid-column: span 2;">
                    <option value="Corte">Gado de Corte</option>
                    <option value="Leite">Gado de Leite</option>
                </select>
            </div>
            <button type="submit" class="btn-add">Cadastrar Cliente no Sistema</button>
        </form>
    </div>
    <div class="card-dark" style="padding: 0;">
        <table>
            <thead><tr><th>Fazenda</th><th>Localização</th><th style="text-align:center;">Ações</th></tr></thead>
            <tbody>
                <?php while($c = $clientes->fetch_assoc()): ?>
                <tr>
                    <td><b><?= $c['nome_fazenda'] ?></b></td>
                    <td>📍 <?= $c['cidade'] ?> - <?= strtoupper($c['uf']) ?></td>
                    <td style="text-align:center;"><a href="?excluir=<?= $c['id'] ?>" style="color:#ef4444; text-decoration:none;" onclick="return confirm('Excluir?')">🗑️</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>