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

$vid = $_SESSION['vendedor_id'];
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$sql = "SELECT id, nome, valor, dias_consumo, estoque, consumo_medio, estoque_minimo, prazo_seguranca 
        FROM produtos 
        WHERE vendedor_id = ? AND ativo = 1";

if ($busca !== '') {
    $sql .= " AND nome LIKE ?";
}

$stmt = $conn->prepare($sql);

if ($busca !== '') {
    $likeBusca = "%$busca%";
    $stmt->bind_param("is", $vid, $likeBusca);
} else {
    $stmt->bind_param("i", $vid);
}

$stmt->execute();
$result = $stmt->get_result();

$produtos = [];
while ($row = $result->fetch_assoc()) {
    $estoque = (int)$row['estoque'];
    $consumo_medio = (float)$row['consumo_medio'];
    $prazo_seguranca = (int)$row['prazo_seguranca'];
    $estoque_minimo = (int)$row['estoque_minimo'];

    // Cálculo da previsão de falta
    $dias_restantes = ($consumo_medio > 0) ? round($estoque / $consumo_medio) : (int)$row['dias_consumo'];
    
    // Define se o estoque está crítico ou saudável
    $estoque_baixo = ($dias_restantes <= $prazo_seguranca || $estoque <= $estoque_minimo);

    $produtos[] = [
        "id" => (int)$row['id'],
        "nome" => $row['nome'],
        "valor" => (float)$row['valor'],
        "estoque" => $estoque,
        "estoque_minimo" => $estoque_minimo,
        "consumo_medio" => $consumo_medio,
        "dias_consumo" => (int)$row['dias_consumo'],
        "dias_restantes" => $dias_restantes,
        "estoque_baixo" => $estoque_baixo,
        "prazo_seguranca" => $prazo_seguranca 
    ];
}

echo json_encode(["produtos" => $produtos]);