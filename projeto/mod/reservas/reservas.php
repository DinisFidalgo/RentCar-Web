<?php
if (isset($_GET['id'])) {
    $idViatura = $_GET['id'];
    $month = date('m');
    $day = date('d');
    $year = date('Y');

    $today = $year . '-' . $month . '-' . $day;

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

            <div class="container">
                <div class="form-container">
                    <h2 class="form-title">Reservar Viatura: <?= htmlspecialchars($row['NomeFabricante']) ?> <?= htmlspecialchars($row['NomeModelo']) ?></h2>
                    <form>
                        <input type="hidden" name="idViatura" value="<?= htmlspecialchars($row['MatriculaViatura']) ?>">

                        <div class="mb-3">
                            <label for="dataInicio" class="form-label">Início da Reserva</label>
                            <input type="date" class="form-control" id="dataInicio" name="dataInicio" required>
                        </div>

                        <div class="mb-3">
                            <label for="dataFim" class="form-label">Fim da Reserva</label>
                            <input type="date" class="form-control" id="dataFim" name="dataFim" required>
                        </div>

                        <div class="mb-3">
                            <label for="localRecolha" class="form-label">Local de Recolha</label>
                            <select class="form-control" id="localRecolha" name="localRecolha" required >
                                <option value="">Selecione o local</option>
                                <option value="Aveiro">Estarreja, Aveiro</option>
                                <option value="Porto">Porto</option>
                                <option value="Lisboa">Lisboa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="localEntrega" class="form-label">Local de Entrega</label>
                            <select class="form-control" id="localEntrega" name="localEntrega" required>
                                <option value="">Selecione o local</option>
                                <option value="Aveiro">Estarreja, Aveiro</option>
                                <option value="Porto">Porto</option>
                                <option value="Lisboa">Lisboa</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-primary" onclick="confirmarReserva()">Confirmar Reserva</button>
                        <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
                    </form>
                </div>
            </div>

            <script>
                const today = new Date();
                today.setDate(today.getDate() + 1); 
                const minDate = today.toISOString().split("T")[0];

                document.getElementById('dataInicio').setAttribute('value', minDate);
                document.getElementById('dataInicio').setAttribute('min', minDate);

                document.getElementById('dataFim').setAttribute('value', minDate);
                document.getElementById('dataFim').setAttribute('min', minDate);

                document.getElementById('dataFim').addEventListener('change', () => {
                    const dataInicio = document.getElementById('dataInicio').value;
                    const dataFim = document.getElementById('dataFim').value;

                    if (dataFim < dataInicio) {
                        alert("A data de fim não pode ser anterior à data de início.");
                        document.getElementById('dataFim').value = dataInicio; 
                    }
                });

                function confirmarReserva() {
                    const idViatura = "<?= urlencode($row['MatriculaViatura']) ?>";
                    const dataInicio = document.getElementById('dataInicio').value;
                    const dataFim = document.getElementById('dataFim').value;
                    const localRecolha = document.getElementById('localRecolha').value;
                    const localEntrega = document.getElementById('localEntrega').value;

                    if (dataInicio && dataFim && localRecolha && localEntrega) {
                        const url = `?m=reservas&a=gestaoReserva&id=${idViatura}&dataInicio=${encodeURIComponent(dataInicio)}&dataFim=${encodeURIComponent(dataFim)}&localRecolha=${encodeURIComponent(localRecolha)}&localEntrega=${encodeURIComponent(localEntrega)}`;
                        window.location.href = url;
                    } else {
                        alert("Preencha todos os campos antes de confirmar a reserva.");
                    }
                }
            </script>
            </body>
            <?php
        } else {
            echo '<div class="alert alert-danger">Viatura não encontrada.</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erro nos detalhes da viatura.</div>';
    }
} else {
    echo '<div class="alert alert-danger">ID da viatura não encontrado.</div>';
}
?>
