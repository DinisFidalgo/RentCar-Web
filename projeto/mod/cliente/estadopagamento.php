<?php
$idCliente = $_SESSION['user_id'];

try {
    $pdo = connectDB($db);

    $sql = "SELECT * 
            FROM Pagamentos
            WHERE Pagamentos.idCliente = :idCliente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
    $stmt->execute();

    $pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao carregar pagamentos: " . $e->getMessage());
    echo '<div class="alert alert-danger">Nos dados. Tente mais tarde.</div>';
    exit();
}
?>

<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Estado dos Pagamentos</h1>

        <?php if (!empty($pagamentos)) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Valor (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagamentos as $pagamento) { ?>
                        <tr>
                            <td><?= $pagamento['estadopagamento'] == 1 ? 'Pago' : 'A aguardar pagamento' ?></td>
                            <td><?= number_format($pagamento['precoFinal'], 2, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info text-center">Nenhum pagamento em atraso.</div>
        <?php } ?>

            <div class="mt-4">
                <a href="?m=ajuda&a=ajudapagamentos" class="btn btn-link">Precisa de ajuda?</a>
            </div>

    </div>
</body>
</html>
