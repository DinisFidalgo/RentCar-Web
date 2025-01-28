<?php

if(isset($_GET['id'])){
    $pagamento = $_GET['id'];
    $pago = 1;
    
    $sql = "UPDATE Pagamentos SET Estadopagamento = :pago WHERE id = :id";
    $stm = $pdo -> prepare($sql);
    $stm -> bindParam(':pago',$pago, PDO::PARAM_INT);
    $stm -> bindParam(':id',$pagamento, PDO::PARAM_INT);
    $stm -> execute();
    
    header('Location: ?m=admin&a=pagamentos');
    
}