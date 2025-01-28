<?php


$matriculaViatura = $_GET['id'];

if (!$matriculaViatura) {
    echo '<div class="alert alert-danger">ID da viatura não foi especificado.</div>';
    exit;
}

try {
    $pdo = connectDB($db);

    $sql = "SELECT * 
            FROM Reserva
            WHERE Viatura_MatriculaViatura = :matriculaViatura";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':matriculaViatura', $matriculaViatura, PDO::PARAM_STR);
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($reservas && count($reservas) > 0) {
        echo '<div class="alert alert-warning">A viatura <strong>' . htmlspecialchars($matriculaViatura) . '</strong> já possui reservas:</div>';
        echo '<ul class="list-group">';
        foreach ($reservas as $reserva) {
            echo '<li class="list-group-item">' . ' A viatura estará disponivel a partir de: ' . htmlspecialchars($reserva['FimReserva']) . ' Dentro de: '. htmlspecialchars($reserva['TempoReserva']) .' dias</li>';
        }
        echo '</ul>';
    } else {
        echo '<div class="alert alert-success">A viatura <strong>' . htmlspecialchars($matriculaViatura) . '</strong> está disponível para reserva.</div>';
    }
} catch (PDOException $e) {
    error_log("Erro ao consultar disponibilidade: " . $e->getMessage());
    echo '<div class="alert alert-danger">Erro ao verificar disponibilidade. Tente novamente mais tarde.</div>';
}

?>
<br>
<a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>

