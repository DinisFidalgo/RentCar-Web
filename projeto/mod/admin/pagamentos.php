<?php
$sqlPagamentos = "SELECT * FROM Pagamentos";
$stmtPagamentos = $pdo->prepare($sqlPagamentos);
$stmtPagamentos->execute();
$pagamentos = $stmtPagamentos->fetchAll(PDO::FETCH_ASSOC);

foreach ($pagamentos as $pagamento) {
    $idReserva = $pagamento['idReserva'];

    $sqlCheckReserva = "SELECT COUNT(*) FROM Reserva WHERE idReserva = :idReserva";
    $stmtCheckReserva = $pdo->prepare($sqlCheckReserva);
    $stmtCheckReserva->execute(['idReserva' => $idReserva]);
    $reservaExists = $stmtCheckReserva->fetchColumn();

    if (!$reservaExists) {
        $sqlDeletePagamento = "DELETE FROM Pagamentos WHERE idReserva = :idReserva";
        $stmtDeletePagamento = $pdo->prepare($sqlDeletePagamento);
        $stmtDeletePagamento->execute(['idReserva' => $idReserva]);
    }
}

$sqlReservas = "SELECT * FROM Reserva";
$stmtReservas = $pdo->prepare($sqlReservas);
$stmtReservas->execute();
$reservas = $stmtReservas->fetchAll(PDO::FETCH_ASSOC);

foreach ($reservas as $reserva) {
    $idReserva = $reserva['idReserva'];
    $idCliente = $reserva['Cliente_idCliente']; 
    $precoReserva = $reserva['Preco']; 
    $sqlCheckPagamento = "SELECT * FROM Pagamentos WHERE idReserva = :idReserva";
    $stmtCheck = $pdo->prepare($sqlCheckPagamento);
    $stmtCheck->execute(['idReserva' => $idReserva]);

    $pagamento = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$pagamento) {
        $sqlInsertPagamento = "INSERT INTO Pagamentos (idReserva, idCliente, precoFinal) 
                               VALUES (:idReserva, :idCliente, :precoFinal)";
        $stmtInsert = $pdo->prepare($sqlInsertPagamento);
        $stmtInsert->execute([
            'idReserva' => $idReserva,
            'idCliente' => $idCliente,
            'precoFinal' => $precoReserva
        ]);
    }
}

$sql = "
    SELECT p.*, c.NomeCliente AS nomeCliente 
    FROM Pagamentos p
    LEFT JOIN Cliente c ON p.idCliente = c.idCliente
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container" style="margin-left: auto; margin-right: auto; margin-top:5vh;">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Id Reserva</th>
                        <th scope="col">Cliente</th> 
                        <th scope="col">Preço Total</th>
                        <th scope="col">Estado Pagamento</th>
                        <th scope="col">Edição</th>
                    </tr>
                </thead>
                <tbody>
<?php
if ($result && count($result) > 0) {
    foreach ($result as $row) {
        $isPago = ($row['estadopagamento'] == 1);
        ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($row['id']) ?></th>
                                <td><?= htmlspecialchars($row['idReserva']) ?></td>
                                <td><?= htmlspecialchars($row['nomeCliente']) ?></td>
                                <td><?= htmlspecialchars($row['precoFinal']) ?></td>
                                <td>
                                    <?php 
                                    if ($isPago) {
                                        $pago = "Pago";
                                    } else {
                                        $pago = "A aguardar pagamento";
                                    }
                                    ?>
                                    <?= $pago ?>
                                </td>
                                <td>
                                    <a 
                                        type="button" 
                                        class="btn btn-success <?= $isPago ? 'disabled' : '' ?>" 
                                        href="<?= $isPago ? '#' : '?m=admin&a=confirmarPagamento&id=' . $row['id'] ?>"
                                        <?= $isPago ? 'aria-disabled="true"' : '' ?>
                                    >
                                        Confirmar Pagamento
                                    </a>
                                </td>
                            </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="6" class="text-center">Sem Pagamentos no sistema</td></tr>';
}
?>
                </tbody>
            </table>
        </div>
    </div>
    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
</div>
