<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include "../includes/conexao.php";

if (!isset($_SESSION['vendedor_id'])) {
    echo json_encode(["erro" => "Não autorizado"]);
    exit;
}

$vid = (int)$_SESSION['vendedor_id'];

$res_cli = $conn->query("SELECT COUNT(*) as qtd FROM clientes WHERE vendedor_id = $vid");
$total_clientes = $res_cli ? $res_cli->fetch_assoc()['qtd'] : 0;

$res_ven = $conn->query("SELECT COUNT(*) as qtd FROM vendas WHERE vendedor_id = $vid");
$total_vendas = $res_ven ? $res_ven->fetch_assoc()['qtd'] : 0;

$res_prod = $conn->query("SELECT COUNT(*) as qtd FROM produtos WHERE vendedor_id = $vid AND ativo = 1");
$total_produtos = $res_prod ? $res_prod->fetch_assoc()['qtd'] : 0;

$res_todos_prod = $conn->query("SELECT id, nome, estoque, consumo_medio, estoque_minimo, prazo_seguranca, valor, dias_consumo FROM produtos WHERE vendedor_id = $vid AND ativo = 1");
$produtos_baixos = [];
$total_estoque_baixo = 0;

if ($res_todos_prod) {
    while($row = $res_todos_prod->fetch_assoc()) {
        $estoque = (int)$row['estoque'];
        $consumo_medio = (float)$row['consumo_medio'];
        $prazo_seguranca = (int)$row['prazo_seguranca'];
        $estoque_minimo = (int)$row['estoque_minimo'];

        $dias_restantes = ($consumo_medio > 0) ? round($estoque / $consumo_medio) : (int)$row['dias_consumo'];
        $estoque_baixo = ($dias_restantes <= $prazo_seguranca || $estoque <= $estoque_minimo);

        if ($estoque_baixo) {
            $total_estoque_baixo++;
            $produtos_baixos[] = [
                "id" => (int)$row['id'],
                "nome" => $row['nome'] ?? 'Insumo',
                "valor" => (float)$row['valor'],
                "estoque" => $estoque,
                "estoque_minimo" => $estoque_minimo,
                "consumo_medio" => $consumo_medio,
                "dias_restantes" => $dias_restantes,
                "estoque_baixo" => true
            ];
        }
    }
}

$res_ultimas = $conn->query("
    SELECT 
        c.nome_fazenda, 
        c.cidade, 
        c.uf, 
        p.nome as produto, 
        v.quantidade, 
        v.data_venda 
    FROM vendas v
    JOIN clientes c ON v.cliente_id = c.id
    JOIN produtos p ON v.produto_id = p.id
    WHERE v.vendedor_id = $vid
    ORDER BY v.id DESC LIMIT 5
");

$ultimas_vendas = [];
if ($res_ultimas) {
    while($row = $res_ultimas->fetch_assoc()) {
        $data_crua = $row['data_venda'];
        $data_formatada = (!empty($data_crua) && $data_crua != '0000-00-00') ? date('d/m/Y', strtotime($data_crua)) : 'Sem data';

        $ultimas_vendas[] = [
            "fazenda" => $row['nome_fazenda'] ?? 'Fazenda Não Identificada',
            "cidade" => $row['cidade'] ?? 'Uberlândia',
            "uf" => strtoupper($row['uf'] ?? 'MG'),
            "produto" => $row['produto'] ?? 'Insumo',
            "quantidade" => (int)$row['quantidade'],
            "data_venda" => $data_formatada
        ];
    }
}

$res_grafico = $conn->query("
    SELECT p.nome, SUM(v.quantidade) as total
    FROM vendas v
    JOIN produtos p ON v.produto_id = p.id
    WHERE v.vendedor_id = $vid
    GROUP BY p.id
    ORDER BY total DESC LIMIT 7
");

$grafico_vendas = [];
if ($res_grafico) {
    while($row = $res_grafico->fetch_assoc()) {
        $grafico_vendas[] = [
            "nome" => $row['nome'] ?? 'Insumo',
            "total" => (int)$row['total']
        ];
    }
}

echo json_encode([
    "cards" => [
        "clientes" => (int)$total_clientes,
        "vendas" => (int)$total_vendas,
        "produtos" => (int)$total_produtos,
        "estoque_baixo" => (int)$total_estoque_baixo
    ],
    "produtos_baixos" => $produtos_baixos,
    "ultimas_vendas" => $ultimas_vendas,
    "grafico_vendas" => $grafico_vendas
]);