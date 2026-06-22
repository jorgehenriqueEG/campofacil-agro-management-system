<?php

include "conexao.php";

$id = $_GET['id'];

$sql = "DELETE FROM clientes WHERE id=$id";

$conn->query($sql);

header("Location: clientes.php");

?>