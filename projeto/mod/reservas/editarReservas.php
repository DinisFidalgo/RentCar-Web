<?php 
if (isset($_GET['id'])) {
    $idReserva = $_GET['id'];
    
    $sql = "SELECT * FROM Reserva WHERE idReserva = :idReserva";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idReserva', $idReserva, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matricula = filter_input(INPUT_POST, 'inputmatricula', FILTER_SANITIZE_STRING);
            $cliente = filter_input(INPUT_POST, 'inputcliente', FILTER_VALIDATE_INT);
            $dias = filter_input(INPUT_POST, 'inputdias', FILTER_SANITIZE_NUMBER_INT);
            $reserva = filter_input(INPUT_POST,'inputreserva', FILTER_SANITIZE_STRING);
            $valor = filter_input(INPUT_POST,'inputvalor', FILTER_SANITIZE_NUMBER_INT);

            $updateReserva = "UPDATE Reserva 
                             SET Viatura_MatriculaViatura = :matricula, Cliente_idCliente = :cliente, TempoReserva = :dias, FimReserva = :reserva,Preco = :valor 
                             WHERE idReserva = :idReserva";
            
            $stm = $pdo->prepare($updateReserva);
            $stm->bindParam(':matricula', $matricula, PDO::PARAM_STR);
            $stm->bindParam(':cliente', $cliente, PDO::PARAM_INT);
            $stm->bindParam(':dias', $dias, PDO::PARAM_INT);
            $stm->bindParam(':reserva', $reserva, PDO::PARAM_STR);
            $stm->bindParam(':valor', $valor);
            $stm->bindParam(':idReserva', $idReserva, PDO::PARAM_INT);
            
            $stm->execute();
            header('Location: ?m=reservas&a=estadoReservas');
            exit;
        }
?>

<form method="post">
    <div class="form-group">
        <label for="inputmatricula">Matricula</label>
        <input type="text" class="form-control" id="inputmatricula" name="inputmatricula" value="<?= htmlspecialchars($row['Viatura_MatriculaViatura']) ?>">
    </div>
    <div class="form-group">
        <label for="inputcliente">Cliente</label>
        <input type="number" class="form-control" id="inputcliente" name="inputcliente" value="<?= htmlspecialchars($row['Cliente_idCliente']) ?>">
    </div>
    <div class="form-group">
        <label for="inputdias">Dias Reservado</label>
        <input type="number" class="form-control" id="inputdias" name="inputdias" value="<?= htmlspecialchars($row['TempoReserva']) ?>">
    </div>
    <div class="form-group">
                <label for="inputreserva">Fim da Reserva</label>
                <input type="date" class="form-control" id="inputreserva" name="inputreserva" value="<?= htmlspecialchars($row['FimReserva']) ?>">
            </div>
    <div class="form-group">
        <div class="form-group">
                    <label for="inputvalor">Valor</label>
                    <input type="text" class="form-control" id="inputvalor" name="inputvalor" value="<?= htmlspecialchars($row['Preco']) ?>">
                </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>

</form>

<?php
    } else {
        echo '<div class="alert alert-danger">ID da Reserva não encontrada.</div>';
    }
}
?>
