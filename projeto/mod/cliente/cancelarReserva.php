<?php

if (isset($_GET['id'],$_GET['matricula'])) {
    $idReserva = $_GET['id'];
    $viatura = $_GET['matricula'];
    $estado = 0;
    $updateReserva = "UPDATE Viatura SET Estado_Reserva = :estado WHERE MatriculaViatura = :viatura";
    
    $stm = $pdo -> prepare($updateReserva);
    $stm-> bindParam(':viatura',$viatura,PDO::PARAM_STR);
    $stm -> bindParam(':estado',$estado, PDO::PARAM_INT);
    $stm->execute();
    
    $deleteReserva = "DELETE FROM Reserva WHERE idReserva = :idReserva";
    
    
    $stmt = $pdo -> prepare($deleteReserva);
    $stmt->bindParam(':idReserva', $idReserva, PDO::PARAM_INT);
    
    $stmt->execute();
   
    
     header('Location: ?m=cliente&a=clienteReservas');
    
}
?>