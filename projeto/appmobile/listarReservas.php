<?php

require_once './config.php';
require_once './core.php';

$pdo = connectDB($db);

$mailCliente = htmlspecialchars(filter_input(INPUT_GET, 'email'));

if (empty($mailCliente)) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "E-mail do cliente não fornecido."]);
    exit;
}

$sqlCliente = "SELECT idCliente FROM Cliente WHERE email = :email";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->bindParam(':email', $mailCliente, PDO::PARAM_STR);
$stmtCliente->execute();
$cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

if (!$cliente || !isset($cliente['idCliente'])) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Cliente não encontrado."]);
    exit;
}

$idCliente = (int) $cliente['idCliente']; 
$sql = "SELECT * FROM Reserva WHERE Cliente_idCliente = :idCliente";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
$stmt->execute();

$result = array();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $tmp = array();
    $tmp['matriculaViatura'] = $row['Viatura_MatriculaViatura'];
    $tmp['FimReserva'] = $row['FimReserva'];
    $tmp['Preco'] = $row['Preco'];
    array_push($result, $tmp);
}

header('Content-Type: application/json');
echo json_encode($result);
