<div class="container" style="margin-left: auto; margin-right: auto; margin-top:5vh;" >
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Matricula</th>
                        <th scope="col">Fabricante & Modelo</th> 
                        <th scope="col">Categoria | Motor | Combustivel | Cor | Ano</th>
                        <th scope="col">Reserva</th>
                        <th scope="col">Edição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     $sql = "SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, 
                               Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, 
                               Fabricante.NomeFabricante
                        FROM Viatura
                        INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
                        INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result && count($result) > 0) {
                        foreach ($result as $row) {
                            ?>
                            <tr>
                                <th scope="row"><?= $row['MatriculaViatura'] ?></th>
                                <td><?= $row['NomeFabricante'] ?> <?= $row['NomeModelo']?></td>
                                <td><?= $row['TipoViatura'] ?> | <?= $row['MotorViatura'] ?> | <?= $row['CombustivelViatura'] ?> | <?= $row['CorViatura'] ?> | <?= $row['AnoViatura'] ?></td>
                                <td><?php $reserva = "";
                    if ($row['Estado_Reserva'] == 1) {
                        $reserva = "Reservado";
                    } else {
                        $reserva = "Sem Reservas";
                    } echo"$reserva"; ?></td>

                                <td>
                                    <a type="button" class="btn btn-primary" href="?m=viaturas&a=editarViatura&id=<?= urlencode($row['MatriculaViatura']) ?>">Editar</a>
                                    <a type="button" class="btn btn-danger" href="">Apagar</a>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<div class="no-results">Sem Viaturas no Sistema( </div>';
                    }
                    ?>

                </tbody>
            </table>
            <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
        </div>
         
    </div>
</div>