<?php

$host = "127.0.0.1";
$user = "root";
$pass = "75396146Jj!";
$db   = "campofacil";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>