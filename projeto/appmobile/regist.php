<?php

require_once './config.php';  
require_once './core.php';    

$pdo = connectDB($db);

$html = "";

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$verifyPassword = filter_input(INPUT_POST, 'verify_password', FILTER_SANITIZE_STRING);

if (!$name) {
    $html .= 'O nome não foi fornecido<br>';
} 
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $html .= 'O email não é válido<br>';
}
if (!$contact) {
   $html .= 'Insira o contacto<br>';
}
if (!$password) {
   $html .= 'Password Não defifnida<br>';
}
if ($password !== $verifyPassword) {
  $html .= 'A Password não coincide<br>';
}

if ($name && $email && $contact && $password === $verifyPassword) {
    try {
        $sql = "SELECT * FROM Cliente WHERE Email = :EMAIL LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $html .= 'O email já está registado<br>';
        } else {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $sqlInsert = "INSERT INTO Cliente (NomeCliente, Email, TeleCliente, PassWord) VALUES (:NAME, :EMAIL, :CONTACT, :PASSWORD)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindValue(":NAME", $name, PDO::PARAM_STR);
            $stmtInsert->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $stmtInsert->bindValue(":CONTACT", $contact, PDO::PARAM_STR);
            $stmtInsert->bindValue(":PASSWORD", $passwordHash, PDO::PARAM_STR);

            if ($stmtInsert->execute()) {
                $html .= "OK"; 
            } else {
                $html .= 'Erro no Registo<br>';
            }
        }
    } catch (PDOException $e) {
        $html .= 'Erro na Base de dados: ' . $e->getMessage() . '<br>';
    }
} else {
   $html .= 'Erro na validação<br>';
}

echo $html;

