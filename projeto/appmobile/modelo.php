<?php

require_once './config.php';
require_once './core.php';

$pdo = connectDB($db);

// SQL query
$sql = "SELECT * FROM Fabricante";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$result = array();


foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $tmp = array();
    $tmp['fabricante'] = $row['NomeFabricante'];
    $tmp['modelo'] = $row['NomeModelo'];
    $tmp['img'] = "https://esan-tesp-ds-paw.web.ua.pt/tesp-ds-g2/projeto/media/".$row['NomeFabricante'].$row['NomeModelo'].".jpg";
    array_push( $result, $tmp );
}

header('Content-Type: application/json');
echo json_encode($result);