<?php

require_once './config.php';
require_once './core.php';

$pdo = connectDB($db);

$html = "";

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $html .= 'O email não é válido<br>';
}

if (!$password) {
    $html .= 'A Pass não fornecida<br>';
}

if ($email && $password) {
    $sql = "SELECT * FROM Cliente WHERE Email = :EMAIL LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() != 1) {
        $html .= 'O email indicado não se encontra registado<br>';
    } else {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($password, $row['PassWord'])) {
            $html .= "Senha incorreta<br>";
        } else {
            $html .= "OK";
        }
    }
} else {
    $html .= "Erro ao processar a solicitação. Parâmetros não recebidos.<br>";
}

echo $html;
