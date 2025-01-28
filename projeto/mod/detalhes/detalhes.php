<?php

if (isset($_GET['id'])) {
    $idViatura = $_GET['id'];

    try {

        $sql = "SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, Fabricante.NomeFabricante
                FROM Viatura
                INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
                INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo
                WHERE Viatura.MatriculaViatura = :idViatura";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idViatura', $idViatura, PDO::PARAM_STR);
        $stmt->execute();
        

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            
                    <div class="container mt-4">                        
                        <div class="card">                            
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['NomeFabricante']) ?> <?= htmlspecialchars($row['NomeModelo']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['TipoViatura']) ?></p>
                                <p class="card-text">Motor: <?= htmlspecialchars($row['MotorViatura']) ?></p>
                                <p class="card-text">Combustível: <?= htmlspecialchars($row['CombustivelViatura']) ?></p>
                                <p class="card-text">Cor: <?= htmlspecialchars($row['CorViatura']) ?></p>
                                <p class="card-text">Ano: <?= htmlspecialchars($row['AnoViatura']) ?></p>
                                <p class="card-text"><?= $row['Estado_Reserva'] == 0 ? 'Disponível para Reserva' : 'Reservado' ?></p>
                                <a href="?m=reservas&a=reservas&id=<?= urlencode($row['MatriculaViatura']) ?>" 
                                               class="btn btn-success <?= $row['Estado_Reserva'] == 1 ? 'disabled' : '' ?>" 
                                               <?= $row['Estado_Reserva'] == 1 ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                                                Reservar
                                            </a>
                                            <a href="?m=reservas&a=disponibilidade&id=<?= urlencode($row['MatriculaViatura']) ?>" class="btn btn-warning">Disponibilidade</a>
                                            <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>                            </div>
                        </div>
                    </div>
                </body>
 
            </html>
            <?php
        } else {
            echo '<div class="alert alert-danger">Viatura não encontrada.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro ao carregar os detalhes da viatura.</div>';
    }
} else {
    echo '<div class="alert alert-danger">ID da viatura não fornecido.</div>';
}
?>
