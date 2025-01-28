<form method="post">
    <select class="form-select" aria-label="Default select example" name="searchmarca" onchange="this.form.submit()">
        <option value="">Todas as Marcas</option>
        <?php 
        $sqlMarcas = "SELECT DISTINCT NomeFabricante FROM Fabricante";
        
        $stmMarcas = $pdo->prepare($sqlMarcas);
        $stmMarcas->execute();
      
        $result = $stmMarcas->fetchAll(PDO::FETCH_ASSOC);
        
        if ($result && count($result) > 0) {
            foreach ($result as $row) {
                $selected = (isset($_POST['searchmarca']) && $_POST['searchmarca'] == $row['NomeFabricante']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($row['NomeFabricante']) . '" ' . $selected . '>' . htmlspecialchars($row['NomeFabricante']) . '</option>';
            }
        }
        ?>
    </select>
</form>

<div class="container mt-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        try {
            $sql = "SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, 
                    Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, 
                    Fabricante.NomeFabricante
                    FROM Viatura
                    INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
                    INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo
                    "; 
            if (isset($_POST['searchmarca']) && !empty($_POST['searchmarca'])) {
                $marcaSelecionada = $_POST['searchmarca'];
                $sql .= " AND Fabricante.NomeFabricante = :marcaSelecionada"; 
            }

            $stmt = $pdo->prepare($sql);
            
            if (isset($marcaSelecionada)) {
                $stmt->bindParam(':marcaSelecionada', $marcaSelecionada, PDO::PARAM_STR);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="./media/<?= htmlspecialchars($row['NomeFabricante']) ?><?= htmlspecialchars($row['NomeModelo']) ?>.jpg"
                                 class="card-img-top" alt="Imagem do carro">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['NomeFabricante'] . ' ' . $row['NomeModelo']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['TipoViatura']) ?></p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><?= $row['Estado_Reserva'] == 0 ? 'Disponível para Reserva' : 'Reservado' ?></li>
                            </ul>
                            <div class="card-body">
                                <a href="?m=detalhes&a=detalhes&id=<?= urlencode($row['MatriculaViatura']) ?>"
                                   class="btn btn-primary">Mais detalhes</a>
                                <a href="?m=reservas&a=reservas&id=<?= urlencode($row['MatriculaViatura']) ?>" 
                                               class="btn btn-success <?= $row['Estado_Reserva'] == 1 ? 'disabled' : '' ?>" 
                                               <?= $row['Estado_Reserva'] == 1 ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                                                Reservar
                                </a>
                                <a href="?m=reservas&a=disponibilidade&id=<?= urlencode($row['MatriculaViatura']) ?>" class="btn btn-warning">Disponibilidade</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="no-results">Nenhum carro disponível no momento ou a pesquisa não encontrou resultados.</div>';
            }
        } catch (PDOException $e) {
            error_log("Erro no banco de dados: " . $e->getMessage());
            echo '<div class="alert alert-danger">Erro ao carregar os dados. Por favor, tente mais tarde.</div>';
        }
        ?>
    </div>
</div>
