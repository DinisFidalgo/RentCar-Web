<?php

require_once './config.php';
require_once './core.php';

header('Content-Type: application/json');
$pdo = connectDB($db);

$response = ["success" => false, "message" => "", "data" => null];

$dataInicio = filter_input(INPUT_POST, 'dataInicio');
$dataFim = filter_input(INPUT_POST, 'dataFim');
$localRecolha = htmlspecialchars(filter_input(INPUT_POST, 'localRecolha'));
$localEntrega = htmlspecialchars(filter_input(INPUT_POST, 'localEntrega'));
$matricula = htmlspecialchars(filter_input(INPUT_POST, 'matricula'));
$mailCliente = htmlspecialchars(filter_input(INPUT_POST, 'mailCliente'));

if (empty($mailCliente)) {
    $response["message"] = "E-mail do cliente não fornecido.";
    echo json_encode($response);
    exit;
}

$sqlCliente = "SELECT idCliente FROM Cliente WHERE email = :email";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->bindParam(':email', $mailCliente, PDO::PARAM_STR);
$stmtCliente->execute();
$cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

if (!$cliente || !isset($cliente['idCliente'])) {
    $response["message"] = "Cliente não encontrado.";
    echo json_encode($response);
    exit;
}

$idCliente = (int) $cliente['idCliente']; 

$today = date('Y-m-d');

if (!$dataInicio || !$dataFim || !$localRecolha || !$localEntrega || !$matricula) {
    $response["message"] = "Parâmetros incompletos.";
    echo json_encode($response);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataInicio) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataFim)) {
    $response["message"] = "O formato das datas deve ser 'yyyy-MM-dd'.";
    echo json_encode($response);
    exit;
}

$dataInicioTimestamp = strtotime($dataInicio);
$todayTimestamp = strtotime($today);

if ($dataInicioTimestamp === false) {
    $response["message"] = "Erro ao interpretar a data de início.";
    error_log("Valor de dataInicio recebido: " . var_export($dataInicio, true));
    echo json_encode($response);
    exit;
}

if ($dataInicioTimestamp < $todayTimestamp) {
    $response["message"] = "A data de início da reserva deve ser hoje ou uma data futura.";
    echo json_encode($response);
    exit;
}

$sql = "SELECT Viatura.MatriculaViatura, Specs.TipoViatura
        FROM Viatura
        INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
        WHERE Viatura.MatriculaViatura = :matricula";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);
$stmt->execute();
$viatura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$viatura) {
    $response["message"] = "Viatura não encontrada.";
    echo json_encode($response);
    exit;
}

function dateDiffInDays($dataInicio, $dataFim) {
    $diff = strtotime($dataFim) - strtotime($dataInicio);
    return max(1, abs(round($diff / 86400)));
}

$totalDias = dateDiffInDays($dataInicio, $dataFim);

$sqlPrecoDia = "SELECT price FROM Prices WHERE categoria = :categoria";
$stmtPrecoDia = $pdo->prepare($sqlPrecoDia);
$stmtPrecoDia->bindParam(':categoria', $viatura['TipoViatura'], PDO::PARAM_STR);
$stmtPrecoDia->execute();
$rowPrecoDia = $stmtPrecoDia->fetch(PDO::FETCH_ASSOC);

if (!$rowPrecoDia || !isset($rowPrecoDia['price'])) {
    $response["message"] = "Preço não encontrado para a categoria.";
    echo json_encode($response);
    exit;
}

$precoTotal = $rowPrecoDia['price'] * $totalDias;

$updateSql = "UPDATE Viatura SET Estado_Reserva = 1 WHERE MatriculaViatura = :matricula";
$updateStmt = $pdo->prepare($updateSql);
$updateStmt->bindParam(':matricula', $matricula, PDO::PARAM_STR);

if ($updateStmt->execute()) {
    $insertSql = "INSERT INTO Reserva (Viatura_MatriculaViatura, Cliente_idCliente, TempoReserva, FimReserva, Preco)
                  VALUES (:viatura, :cliente, :tempo, :datafim, :precofinal)";
    $insertStmt = $pdo->prepare($insertSql);
    $insertStmt->bindParam(':viatura', $matricula, PDO::PARAM_STR);
    $insertStmt->bindParam(':cliente', $idCliente, PDO::PARAM_INT);
    $insertStmt->bindParam(':tempo', $totalDias, PDO::PARAM_INT);
    $insertStmt->bindParam(':datafim', $dataFim, PDO::PARAM_STR);
    $insertStmt->bindParam(':precofinal', $precoTotal, PDO::PARAM_INT);

    if ($insertStmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Reserva criada com sucesso.";
        $response["data"] = [
            "viatura" => $matricula,
            "cliente" => $idCliente,
            "dias" => $totalDias,
            "precoTotal" => $precoTotal,
            "dataFim" => $dataFim
        ];
    } else {
        $response["message"] = "Erro ao criar a reserva.";
    }
} else {
    $response["message"] = "Erro ao atualizar o estado da viatura.";
}

echo json_encode($response);
