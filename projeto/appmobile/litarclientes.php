<?php

require_once './config.php';
require_once './core.php';

$pdo = connectDB($db);

$sql = "SELECT * FROM Cliente";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$result = array();


foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $tmp = array();
    $tmp['nome'] = $row['NomeCliente'];
    $tmp['email'] = $row['Email'];
    $tmp['contacto'] = $row['TeleCliente'];
 //   $tmp['idCliente'] = $row['idCliente'];
    array_push( $result, $tmp );
}

header('Content-Type: application/json');
echo json_encode($result);