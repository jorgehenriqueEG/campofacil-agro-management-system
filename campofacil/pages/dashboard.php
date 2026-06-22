<?php

include "../includes/verificar_login.php";
include "../includes/menu.php";

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Dashboard</title>

<style>

body{
    background:#0f172a;
    color:#f8fafc;
    margin:0;
    font-family:Arial;
}

.main-content{
    margin-left:260px;
    padding:40px;
    min-height:100vh;
}

h2{
    margin-bottom:30px;
}

.card-box{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:30px;
}

.card-stats{
    background:#1e293b;
    padding:25px;
    border-radius:12px;
    border-bottom:4px solid #4ade80;
}

.card-number{
    font-size:2rem;
    font-weight:bold;
    margin-bottom:10px;
}

.card-title{
    color:#94a3b8;
    font-size:0.8rem;
    text-transform:uppercase;
}

.table-box{
    background:#1e293b;
    border-radius:12px;
    overflow:hidden;
    margin-bottom:30px;
}

.table-header{
    padding:20px;
    border-bottom:1px solid #334155;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    padding:15px;
    text-align:left;
    color:#94a3b8;
    border-bottom:1px solid #334155;
}

td{
    padding:15px;
    border-bottom:1px solid #334155;
}

.tag-location{
    color:#818cf8;
}

.tag-qtd{
    background:#064e3b;
    color:#4ade80;
    padding:4px 8px;
    border-radius:4px;
    font-weight:bold;
}

.alerta-item{
    background:#7f1d1d;
    color:#fecaca;
    padding:15px;
    margin:15px;
    border-radius:8px;
    font-weight:bold;
}

.alerta-ok{
    background:#14532d;
    color:#bbf7d0;
    padding:15px;
    margin:15px;
    border-radius:8px;
    font-weight:bold;
}

canvas{
    width:100% !important;
    max-height:400px;
}

</style>

</head>

<body>

<div class="main-content">

<h2>📊 PAINEL GERAL</h2>

<div class="card-box">

    <div class="card-stats">

        <div
        class="card-number"
        id="card-clientes"
        >
            0
        </div>

        <div class="card-title">
            Clientes
        </div>

    </div>

    <div class="card-stats">

        <div
        class="card-number"
        id="card-vendas"
        >
            0
        </div>

        <div class="card-title">
            Vendas
        </div>

    </div>

    <div class="card-stats">

        <div
        class="card-number"
        id="card-produtos"
        >
            0
        </div>

        <div class="card-title">
            Produtos
        </div>

    </div>

    <div class="card-stats">

        <div
        class="card-number"
        id="card-estoque"
        >
            0
        </div>

        <div class="card-title">
            Estoque Baixo
        </div>

    </div>

</div>

<div class="table-box">

    <div class="table-header">
        <h3>⚠ Produtos com Estoque Baixo</h3>
    </div>

    <div id="produtos-baixos"></div>

</div>

<div class="table-box">

    <div class="table-header">
        <h3>🕒 Últimas Vendas</h3>
    </div>

    <table>

        <thead>

            <tr>

                <th>Fazenda</th>

                <th>Localização</th>

                <th>Produto</th>

                <th>Quantidade</th>

                <th>Data</th>

            </tr>

        </thead>

        <tbody id="tbody-vendas"></tbody>

    </table>

</div>

<div class="table-box">

    <div class="table-header">
        <h3>📈 Produtos Mais Vendidos</h3>
    </div>

    <div style="padding:20px;">

        <canvas id="graficoVendas"></canvas>

    </div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="../assets/js/dashboard.js"></script>

</body>
</html>