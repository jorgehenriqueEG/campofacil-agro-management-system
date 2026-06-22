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
        UPDATE produtos
        SET ativo = 0
        WHERE id = ?
        AND vendedor_id = ?
    ");

    $stmt->bind_param("ii", $id, $vid);
    $stmt->execute();

    header("Location: produtos.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nome = trim($_POST['nome']);

    $valor = (float)$_POST['valor'];

    $dias = (int)$_POST['dias'];

    $estoque = (int)$_POST['estoque'];

    $consumo_medio = (float)$_POST['consumo_medio'];

    $estoque_minimo = (int)$_POST['estoque_minimo'];

    $prazo_seguranca = (int)$_POST['prazo_seguranca'];

    if($consumo_medio <= 0){
        $consumo_medio = 1;
    }

    if($estoque_minimo <= 0){
        $estoque_minimo = 5;
    }

    if($prazo_seguranca <= 0){
        $prazo_seguranca = 7;
    }

    $sql = "
    INSERT INTO produtos
    (
        vendedor_id,
        nome,
        valor,
        dias_consumo,
        estoque,
        consumo_medio,
        estoque_minimo,
        prazo_seguranca
    )

    VALUES

    (?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "isdiidii",
        $vid,
        $nome,
        $valor,
        $dias,
        $estoque,
        $consumo_medio,
        $estoque_minimo,
        $prazo_seguranca
    );

    if($stmt->execute()){

        $sucesso = "Produto cadastrado com sucesso.";

    }else{

        $erro = "Erro ao cadastrar produto.";
    }
}

include "../includes/menu.php";

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Produtos</title>

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
    padding:25px;
    border-radius:12px;
    margin-bottom:25px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    background:#0f172a;
    border:1px solid #334155;
    color:white;
    border-radius:8px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:8px;
    background:#8DB600;
    color:white;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    opacity:0.9;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:20px;
}

.prod{
    background:#1e293b;
    border-left:5px solid #8DB600;
    padding:20px;
    border-radius:12px;
    position:relative;
}

.prod-baixo{
    border-left:5px solid #ef4444;
}

.badge{
    display:inline-block;
    padding:6px 10px;
    border-radius:8px;
    font-size:0.8rem;
    font-weight:bold;
    margin-top:10px;
}

.badge-ok{
    background:#14532d;
    color:#bbf7d0;
}

.badge-baixo{
    background:#7f1d1d;
    color:#fecaca;
}

h2{
    color:#4ade80;
}

a{
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

.busca{
    margin-bottom:25px;
}

.loading{
    background:#1e293b;
    padding:20px;
    border-radius:10px;
    text-align:center;
    color:#94a3b8;
}

.sem-produtos{
    background:#1e293b;
    padding:20px;
    border-radius:10px;
    text-align:center;
    color:#94a3b8;
}

.info{
    color:#94a3b8;
    font-size:0.9rem;
    margin-top:5px;
}

</style>

</head>

<body>

<div class="main-content">

<h2>📦 Produtos</h2>

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

<input
type="text"
name="nome"
placeholder="Nome do Produto"
required
>

<input
type="number"
step="0.01"
name="valor"
placeholder="Valor"
required
>

<input
type="number"
name="dias"
placeholder="Dias Base de Consumo"
required
>

<input
type="number"
name="estoque"
placeholder="Estoque"
required
>

<input
type="number"
step="0.1"
name="consumo_medio"
placeholder="Consumo Médio por Dia"
required
>

<input
type="number"
name="estoque_minimo"
placeholder="Estoque Mínimo"
required
>

<input
type="number"
name="prazo_seguranca"
placeholder="Prazo de Segurança (dias)"
required
>

<div class="info">
Exemplo: avisar quando faltar menos de 7 dias de estoque.
</div>

<br>

<button type="submit">
Cadastrar Produto
</button>

</form>

</div>

<input
type="text"
id="buscaProduto"
class="busca"
placeholder="Buscar produto..."
>

<div
class="grid"
id="listaProdutos"
>

<div class="loading">
Carregando produtos...
</div>

</div>

</div>

<script>

const busca = document.getElementById('buscaProduto');

const lista = document.getElementById('listaProdutos');

let produtosAPI = [];

async function carregarProdutos(){

    try{

        const resposta =
        await fetch('../api/produtos.php');

        const dados =
        await resposta.json();

        produtosAPI = dados.produtos;

        renderizarProdutos(produtosAPI);

    }catch(erro){

        console.error(erro);

        lista.innerHTML = `

            <div class="alerta erro">
                Erro ao carregar produtos.
            </div>

        `;
    }
}

function renderizarProdutos(produtos){

    lista.innerHTML = '';

    if(produtos.length === 0){

        lista.innerHTML = `

            <div class="sem-produtos">
                Nenhum produto encontrado.
            </div>

        `;

        return;
    }

    produtos.forEach(produto => {

        const badge = produto.estoque_baixo

        ? `

            <div class="badge badge-baixo">
                🔴 Estoque Crítico
            </div>

        `

        : `

            <div class="badge badge-ok">
                🟢 Estoque Saudável
            </div>

        `;

        const classe = produto.estoque_baixo

        ? 'prod prod-baixo'

        : 'prod';

        lista.innerHTML += `

        <div class="${classe}">

            <h3>${produto.nome}</h3>

            <p>
                 R$ ${Number(produto.valor)
                .toFixed(2)
                .replace('.', ',')}
            </p>

            <p>
                 Estoque:
                <strong>${produto.estoque}</strong>
            </p>

            <p>
                 Mínimo:
                ${produto.estoque_minimo}
            </p>

            <p>
                 Consumo:
                ${produto.consumo_medio}/dia
            </p>

            <p>
          Segurança:
         ${produto.prazo_seguranca || 0} dias
        </p>

            <p>
                 Duração estimada:
                ${produto.dias_restantes} dias
            </p>

            ${badge}

            <br><br>

            <a
            href="?excluir=${produto.id}"
            onclick="return confirm('Desativar produto?')"
            >
            Desativar
            </a>

        </div>

        `;
    });
}

busca.addEventListener('keyup', function(){

    const texto =
    busca.value.toLowerCase();

    const filtrados =
    produtosAPI.filter(produto => {

        return produto.nome
        .toLowerCase()
        .includes(texto);

    });

    renderizarProdutos(filtrados);

});

carregarProdutos();

setInterval(carregarProdutos, 15000);

</script>

</body>
</html>