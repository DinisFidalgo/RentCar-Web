<?php
define('DESC', 'Registar um novo utilizador');
define('UC', 'PAW');
$html = '';

require_once './config.php';
require_once './core.php';

$register = filter_input(INPUT_POST, 'register');
if ($register) {
    $pdo = connectDB($db);

    $username = filter_input(INPUT_POST, 'NomeCliente', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'PassWord');
    $password_confirmar = filter_input(INPUT_POST, 'password_confirmar');
    $contact = filter_input(INPUT_POST, 'TeleCliente', FILTER_SANITIZE_NUMBER_INT);
    $password_hash_db = password_hash($password, PASSWORD_ARGON2ID);

    $errors = false;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $html .= '<div class="alert alert-danger">O email não é válido.</div>';
        $errors = true;
    }

    if ($username == '') {
        $html .= '<div class="alert alert-danger">Tem que definir um username.</div>';
        $errors = true;
    }

    if (strlen($password) < 8) {
        $html .= '<div class="alert alert-danger">Palavra-passe tem menos de 8 caracteres.</div>';
        $errors = true;
    }

    if ($password !== $password_confirmar) {
        $html .= '<div class="alert alert-danger">As senhas não coincidem.</div>';
        $errors = true;
    }

    if (strlen($contact) < 9) {
        $html .= '<div class="alert alert-danger">Contacto inválido.</div>';
        $errors = true;
    }

    $concordo = filter_input(INPUT_POST, 'concordo');
    if (!$concordo) {
        $html .= '<div class="alert alert-danger">Você precisa concordar com os termos de utilização.</div>';
        $errors = true;
    }

    $sql = "SELECT idCliente FROM Cliente WHERE Email = :EMAIL LIMIT 1";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $html .= '<div class="alert alert-danger">O email indicado já se encontra registado.</div>';
            $errors = true;
        }
    } catch (PDOException $e) {
        $html .= '<div class="alert alert-danger">Ocorreu um erro. Por favor tente mais tarde.</div>';
    }

    if (!$errors) {
        $sql = "INSERT INTO Cliente(NomeCliente, Email, TeleCliente, PassWord) VALUES(:USERNAME, :EMAIL, :CONTACT, :PASSWORD)";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":USERNAME", $username, PDO::PARAM_STR);
            $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $stmt->bindValue(":CONTACT", $contact, PDO::PARAM_STR);
            $stmt->bindValue(":PASSWORD", $password_hash_db, PDO::PARAM_STR);

            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $html .= '<div class="alert alert-success">Utilizador criado com sucesso! <a class="btn btn-primary" href="./login.php">Login</a></div>';
            } else {                
                $html .= '<div class="alert alert-danger">Erro ao inserir na Base de Dados.</div>';
            }
        } catch (PDOException $e) {
            $html .= '<div class="alert alert-danger">Ocorreu um erro. Por favor tente mais tarde.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mário Pinto">
        <title>Registo de novo utilizador</title>
        <link rel="icon" type="image/x-icon" href="media/ToyotaAE86.jpg">


        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    </head>
    <body class="text-center" style="background-attachment: fixed; background-repeat: no-repeat; background-size: cover;">
        <main class="form-signin w-100 m-auto">
            <form method="post" action="">
                <a href="welcome.php" style="text-decoration: none; color: black"><h1>RENT CAR</h1></a>
                <hr>
                <h1 class="h3 mb-3 fw-normal">Registo de novo utilizador</h1>

                <div class="form-floating">
                    <input type="text" name="NomeCliente" class="form-control" id="floatingNome" placeholder="nome" required="">
                    <label for="floatingNome">Nome</label>
                </div>
                <div class="form-floating">
                    <input type="email" name="Email" class="form-control" id="floatingInput" placeholder="name@example.com" required="">
                    <label for="floatingInput">Email</label>
                </div>
                <div class="form-floating">
                    <input type="text" name="TeleCliente" class="form-control" id="floatingInput2" placeholder="916415152" required="">
                    <label for="floatingInput2">Contacto</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="PassWord" class="form-control" id="floatingPassword" placeholder="Password" required="">
                    <label for="floatingPassword">Password</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="password_confirmar" class="form-control" id="floatingPasswordConfirmar" placeholder="Password" required="">
                    <label for="floatingPasswordConfirmar">Confirmar a Password</label>
                </div>

                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" id="concordo" value="concordo" name="concordo" required=""> Concordo com os <a href="#">termos de utilização</a>
                    </label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit" name="register" value="register">Registar</button>
            </form>
            <div><?= $html ?></div>
            <hr>
            <a class="btn btn-secondary" href="login.php">Login</a>
        </main>
    </body>
</html>
<style>
    .body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: yellow;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .form-signin {
        top: 25%;
        width: 100%;
        max-width: 400px;
        padding: 20px;
        background-color: whitesmoke;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>
