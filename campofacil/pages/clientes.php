<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../includes/conexao.php";
include "../includes/verificar_login.php";

$vid = $_SESSION['vendedor_id'] ?? 0;

$ufs = [
    "AC","AL","AP","AM","BA","CE","DF","ES","GO",
    "MA","MT","MS","MG","PA","PB","PR","PE","PI",
    "RJ","RN","RS","RO","RR","SC","SP","SE","TO"
];

if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ? AND vendedor_id = ?");
    $stmt->bind_param("ii", $id, $vid);
    $stmt->execute();
    header("Location: clientes.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome_fazenda = trim($_POST['nome_fazenda']);
    $cidade = trim($_POST['cidade']);
    $uf = strtoupper(trim($_POST['uf']));
    $cpf_cnpj = preg_replace('/\D/', '', $_POST['cpf_cnpj']);
    $email = trim($_POST['email']);
    $telefone = preg_replace('/\D/', '', $_POST['telefone']);
    $tipo_animal = $_POST['tipo_animal'];
    $tipo_gado = $_POST['tipo_gado'];

    $quantidade_animais = 1;
    $forma_pagamento = "Não informado";

    if(!in_array($uf, $ufs)){
        $erro = "UF inválida.";
    } elseif(strlen($cpf_cnpj) < 11){
        $erro = "CPF/CNPJ inválido.";
    } else {
        $sql = "INSERT INTO clientes (vendedor_id, nome_fazenda, cidade, uf, cpf_cnpj, email, telefone, tipo_animal, tipo_gado, quantidade_animais, forma_pagamento) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssssis", $vid, $nome_fazenda, $cidade, $uf, $cpf_cnpj, $email, $telefone, $tipo_animal, $tipo_gado, $quantidade_animais, $forma_pagamento);

        if($stmt->execute()){
            $sucesso = "Cliente cadastrado com sucesso.";
        } else {
            $erro = "Erro ao cadastrar cliente.";
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM clientes WHERE vendedor_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $vid);
$stmt->execute();
$clientes = $stmt->get_result();

include "../includes/menu.php";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes - CampoFácil</title>
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
.card-dark{
    background:#1e293b;
    border:1px solid #334155;
    padding:30px;
    border-radius:12px;
    margin-bottom:30px;
}
h2{
    color:#4ade80;
    margin-bottom:25px;
}
.grid-form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}
input, select{
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
    font-weight:bold;
    width:100%;
    margin-top:20px;
    cursor:pointer;
    transition:0.3s;
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
    color:#94a3b8;
    font-size:0.8rem;
}
td{
    padding:15px;
    border-bottom:1px solid #334155;
}
.btn-delete{
    color:#ef4444;
    text-decoration:none;
    font-size:1.1rem;
}
</style>
</head>
<body>

<div class="main-content">

    <h2>👥 Gerenciar Clientes</h2>

    <div class="card-dark">
        <form method="POST">
            <div class="grid-form">
                <input type="text" name="nome_fazenda" placeholder="Nome do cliente" required>
                <input type="text" name="cidade" placeholder="Cidade" required>
                <select name="uf" required>
                    <option value="">Selecionar UF</option>
                    <?php foreach($ufs as $estado): ?>
                        <option value="<?= $estado ?>"><?= $estado ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="cpf_cnpj" placeholder="CPF ou CNPJ" required>
                <input type="email" name="email" placeholder="E-mail">
                <input type="text" name="telefone" placeholder="Telefone" required>
                <select name="tipo_animal">
                    <option value="Gado">Gado</option>
                    <option value="Aves">Aves</option>
                    <option value="Suinos">Suínos</option>
                    <option value="Cavalos">Cavalos</option>
                    <option value="Outros">Outros</option>
                </select>
                <select name="tipo_gado">
                    <option value="Corte">Gado de Corte</option>
                    <option value="Leite">Gado de Leite</option>
                </select>
            </div>
            <button type="submit">Cadastrar Cliente</button>
        </form>
    </div>

    <div class="card-dark" style="padding:0; overflow:hidden;">
        <table>
            <thead>
                <tr>
                    <th>Fazenda</th>
                    <th>Cidade</th>
                    <th>UF</th>
                    <th>Telefone</th>
                    <th>Animal</th>
                    <th style="text-align:center;">Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php while($c = $clientes->fetch_assoc()): ?>
                <tr>
                    <td><b><?= htmlspecialchars($c['nome_fazenda']) ?></b></td>
                    <td><?= htmlspecialchars($c['cidade']) ?></td>
                    <td><?= strtoupper($c['uf']) ?></td>
                    <td><?= htmlspecialchars($c['telefone']) ?></td>
                    <td><?= htmlspecialchars($c['tipo_animal']) ?></td>
                    <td style="text-align:center;">
                        <a href="?excluir=<?= $c['id'] ?>" class="btn-delete" onclick="return confirm('Deseja excluir este cliente?')">🗑️</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if(!empty($erro)): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof criarToast === "function") criarToast(<?= json_encode($erro) ?>, 'erro');
    });
</script>
<?php endif; ?>

<?php if(!empty($sucesso)): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof criarToast === "function") criarToast(<?= json_encode($sucesso) ?>, 'sucesso');
    });
</script>
<?php endif; ?>

</body>
</html>