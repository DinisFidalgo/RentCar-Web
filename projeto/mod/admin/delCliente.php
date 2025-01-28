<?php

if (isset($_GET['id'])) {
$idCliente = $_GET['id'];

$sql = "DELETE FROM Cliente WHERE idCliente = :idCliente";

$stm = $pdo -> prepare($sql);
$stm -> bindParam(':idCliente',$idCliente, PDO::PARAM_INT);
$stm -> execute();
header('Location: ?m=cliente&a=clientes');


}