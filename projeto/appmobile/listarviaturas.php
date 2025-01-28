<?php

require_once './config.php';
require_once './core.php';

$pdo = connectDB($db);

$sql = "SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, 
                               Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, 
                               Fabricante.NomeFabricante
                        FROM Viatura
                        INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
                        INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo
                        WHERE Viatura.Estado_Reserva = 0";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$result = array();


foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $tmp = array();
    $tmp['fabricante'] = $row['NomeFabricante'];
    $tmp['modelo'] = $row['NomeModelo'];
    $tmp['reserva'] = $row['Estado_Reserva'];
    $tmp['categoria'] = $row['TipoViatura'];
    $tmp['motor'] = $row['MotorViatura'];
    $tmp['cor'] = $row['CorViatura'];
    $tmp['combustivel'] = $row['CombustivelViatura'];
    $tmp['ano'] = $row['AnoViatura'];
    $tmp['img'] = "https://esan-tesp-ds-paw.web.ua.pt/tesp-ds-g2/projeto/media/".$row['NomeFabricante'].$row['NomeModelo'].".jpg";
    $tmp['matricula'] = $row['MatriculaViatura'];

    array_push( $result, $tmp );
}

header('Content-Type: application/json');
echo json_encode($result);