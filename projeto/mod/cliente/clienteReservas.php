<?php
try {
    $idCliente = $_SESSION['user_id'];
    $sqlUltimaAtualizacao = "SELECT ultima_atualizacao FROM AtualizaReservas ORDER BY id DESC LIMIT 1";
    $stmt = $pdo->query($sqlUltimaAtualizacao);
    $ultimaAtualizacao = $stmt->fetchColumn();

    $dataAtual = new DateTime();
    $dataUltimaAtualizacao = new DateTime($ultimaAtualizacao);

    $intervalo = $dataAtual->diff($dataUltimaAtualizacao);
    if ($intervalo->days >= 1) {
        $pdo->beginTransaction();

        $sqlAtualizarReservas = "
            UPDATE Reserva
            SET TempoReserva = TempoReserva - 1
            WHERE TempoReserva > 0;
        ";
        $pdo->exec($sqlAtualizarReservas);

        $sqlExcluirReservas = "
            DELETE FROM Reserva
            WHERE TempoReserva <= 0;
        ";
        $pdo->exec($sqlExcluirReservas);

        $sqlViaturas = "
            UPDATE Viatura
            SET Estado_Reserva = 0
            WHERE MatriculaViatura NOT IN (
                SELECT Viatura_MatriculaViatura FROM Reserva
            )
        ";
        $pdo->exec($sqlViaturas);

        $sqlAtualizarDatas = "
            UPDATE AtualizaReservas
            SET ultima_atualizacao = NOW()
            WHERE id = 1;
        ";
        $pdo->exec($sqlAtualizarDatas);

        $pdo->commit();
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Erro ao atualizar reservas: " . $e->getMessage();
}
?>

<div class="container" style="margin-left: auto; margin-right: auto; margin-top:5vh;">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Matricula</th>
                        <th scope="col">Fim da Reserva</th>
                        <th scope="col">Cancelamento</th>
                    </tr>
                </thead>
                <tbody>
<?php
 $sql = "
        SELECT Reserva.idReserva,Reserva.Viatura_MatriculaViatura, Reserva.Cliente_idCliente,Reserva.TempoReserva,Reserva.FimReserva,Cliente.NomeCliente
        FROM Reserva
        INNER JOIN Cliente ON Reserva.Cliente_idCliente = Cliente.idCliente
        WHERE Reserva.Cliente_idCliente = :idCliente
    ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($result && count($result) > 0) {
    foreach ($result as $row) {
        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Viatura_MatriculaViatura']) ?></td>
                                <td><?= htmlspecialchars($row['FimReserva']) ?></td>
                                <td>
                                    <a type="button" class="btn btn-danger" href="?m=cliente&a=cancelarReserva&id=<?= $row['idReserva'] ?>&matricula=<?= $row['Viatura_MatriculaViatura']?>">Cancelar</a>
                                </td>
                            </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="6" class="text-center">Sem Reservas no Sistema</td></tr>';
}
?>
                </tbody>
            </table>
        </div>
    </div>
     <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
</div>
<script>
        function confirmarReserva() {
            const idViatura = "<?= urlencode($row['MatriculaViatura']) ?>";
            const idReserva = "<?= urlencode($row['idReserva'])?>";
            
            
            const url = `?m=admin&a=deleteReserva&id=${idReserva}&matricula=${idViatura}`;
            window.location.href = url;
           

    </script>
