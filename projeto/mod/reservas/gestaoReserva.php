<?php


if (isset($_GET['id'], $_GET['dataInicio'], $_GET['dataFim'], $_GET['localRecolha'], $_GET['localEntrega'])) {
    $idViatura = htmlspecialchars($_GET['id']);
    $dataInicio = htmlspecialchars($_GET['dataInicio']);
    $dataFim = htmlspecialchars($_GET['dataFim']);
    $localRecolha = htmlspecialchars($_GET['localRecolha']);
    $localEntrega = htmlspecialchars($_GET['localEntrega']);


    $sql = "SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, 
                    Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, 
                    Fabricante.NomeFabricante
            FROM Viatura
            INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
            INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo
            WHERE Viatura.MatriculaViatura = :idViatura";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idViatura', $idViatura, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    function dateDiffInDays($dataInicio, $dataFim) {
        
            $diff = strtotime($dataFim) - strtotime($dataInicio);
            $dias = abs(round($diff / 86400));

            return ($dias == 0) ? 1 : $dias;
        

    }
    
    $totalDias = dateDiffInDays($dataInicio, $dataFim);
   
    $sqlTipoViatura = "SELECT Specs.TipoViatura 
                   FROM Viatura 
                   INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec 
                   WHERE Viatura.MatriculaViatura = :idViatura";
    $stmtTipoViatura = $pdo->prepare($sqlTipoViatura);
    $stmtTipoViatura->bindParam(':idViatura', $idViatura, PDO::PARAM_STR);
    $stmtTipoViatura->execute();

    $tipoViatura = $stmtTipoViatura->fetchColumn();

    if ($tipoViatura) {
        $sqlPrecoDia = "SELECT price FROM Prices WHERE categoria = :categoria";
        $stmtPrecoDia = $pdo->prepare($sqlPrecoDia);
        $stmtPrecoDia->bindParam(':categoria', $tipoViatura, PDO::PARAM_STR);
        $stmtPrecoDia->execute();

        $rowPrecoDia = $stmtPrecoDia->fetch(PDO::FETCH_ASSOC);

        if ($rowPrecoDia && isset($rowPrecoDia['price'])) {
            $precoDia = $rowPrecoDia['price'];
        } else {
            echo "Preço não encontrado";
        }
    } else {
        echo "Categoria não encontrada.";
    }

    $precoTotal = $rowPrecoDia['price'] * $totalDias;
    
    $confirmar = filter_input(INPUT_POST,'confirmar');
    if ($confirmar) {
        
        $updateSql = "UPDATE Viatura SET Estado_Reserva = 1 WHERE MatriculaViatura = :idViatura";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':idViatura', $idViatura, PDO::PARAM_STR);

        if ($updateStmt->execute()) {
            $tempoReserva = $totalDias;

            $idCliente = $_SESSION['user_id'];

            $insertSql = "INSERT INTO Reserva (Viatura_MatriculaViatura, Cliente_idCliente, TempoReserva, FimReserva, Preco) 
                  VALUES (:viatura, :cliente, :tempo, :datafim, :precofinal)";
            $insertStmt = $pdo->prepare($insertSql); 
            $insertStmt->bindParam(':viatura', $idViatura, PDO::PARAM_STR);
            $insertStmt->bindParam(':cliente', $idCliente, PDO::PARAM_INT);
            $insertStmt->bindParam(':tempo', $tempoReserva, PDO::PARAM_INT);
            $insertStmt -> bindParam(':datafim', $dataFim, PDO::PARAM_STR);
            $insertStmt -> bindParam(':precofinal',$precoTotal, PDO::PARAM_INT);
            

            if ($insertStmt->execute()) {
                header('Refresh:1 ; URL= index.php');
                echo '<div class="alert alert-success">Sucesso na reserva e registo atualizado.</div>';
            } else {
                echo '<div class="alert alert-danger">Erro ao registar a reserva. Por favor, tente novamente.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Erro ao confirmar a reserva. Por favor, tente novamente.</div>';
        }
    }
} else {
    echo '<div class="alert alert-danger">Informação de reserva incompleta. Por favor, verifique os detalhes da reserva.</div>';
    exit;
}
?>



    <style>
        .container {
            margin-top: 30px;
        }
        .details-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
        }
        .details-title {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="details-container">
            <h2 class="details-title">Detalhes da Reserva</h2>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Viatura</th>
                        <td><?= htmlspecialchars($row['NomeFabricante']) ?> <?= htmlspecialchars($row['NomeModelo']) ?></td>
                    </tr>
                    <tr>
                        <th>Matricula</th>
                        <td><?= htmlspecialchars($idViatura) ?></td>
                    </tr>
                    <tr>
                        <th>Início da Reserva</th>
                        <td><?= htmlspecialchars($dataInicio) ?></td>
                    </tr>
                    <tr>
                        <th>Fim da Reserva</th>
                        <td><?= htmlspecialchars($dataFim) ?></td>
                    </tr>
                    <tr>
                        <th>Local de Recolha</th>
                        <td><?= htmlspecialchars($localRecolha) ?></td>
                    </tr>
                    <tr>
                        <th>Local de Entrega</th>
                        <td><?= htmlspecialchars($localEntrega) ?></td>
                    </tr>
                    <tr>
                        <th>Preço total</th>
                        <td><?= htmlspecialchars($precoTotal) ?> €</td>
                    </tr>
                </tbody>
            </table>
            <form action="" method="post">
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success" name="confirmar" value="confirmar" >Confirmar Reserva</button>
                    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</body>
