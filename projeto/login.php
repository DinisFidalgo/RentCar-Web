<?php
session_start();
define('DESC', 'Fazer login de um utilizador');
define('UC', 'PAW');
$html = '';

require_once './config.php';
require_once './core.php';

$login = filter_input(INPUT_POST, 'login');
if ($login) {
    $pdo = connectDB($db);

    $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST,'PassWord');
    $password_hash_db = password_hash($password, PASSWORD_DEFAULT);

    $errors = false;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $html .= '<div class="alert alert-danger">O email não é válido.</div>';
        $errors = true;
    }

    if (!$errors) {
        $sql = "SELECT * FROM `Cliente` WHERE `Email` = :EMAIL LIMIT 1";
        try {
            $stmt = $pdo ->prepare($sql);
            $stmt->bindValue(":EMAIL", $email, PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt ->rowCount() != 1) {
                $html .= '<div class="alert alert-danger">O email indicado não se encontra registado.</div>';
                $errors = true;
            } else {
                $row = $stmt-> fetch();
              
            }
        } catch (PDOException $e) {
            $errors = true;
            $html .= '<div class="alert alert-danger">Ocorreu um erro. Por favor tente mais tarde.</div>';
        }
    }

    if (!$errors) {
        if (!password_verify($password, $row['PassWord'])) {
            $html .= '<div class="alert alert-danger">Palavra-passe incorreta.</div>';
            sleep(random_int(1, 3));
        } else {
            $_SESSION['NomeCliente']= $row['NomeCliente'];
            $_SESSION['EmailCliente'] = $row['Email'];
            $_SESSION['user_id'] = $row['idCliente'];
            $_SESSION['admin'] = $row['IsAdmin'];
            $html .= '<div class="alert alert-success">Login com sucesso! <br> <b>' . $_SESSION['NomeCliente'] . '</b><br>';
            $html .= '<a href="index.php" class="btn btn-primary">Continuar</a></div>'; 
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
        <title>Iniciar Sessão</title>
        <link rel="icon" type="image/x-icon" href="media/ToyotaAE86.jpg">


        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

       
        <meta name="theme-color" content="#712cf9">

    </head>
    <body class="text-center" style="background-image: url('Images/toyota-corolla-e11.jpg'); background-attachment: fixed;
                                     background-repeat: no-repeat; background-size: cover;">
        <main class="form-signin w-400 m-auto">
            
            <form action="WelcomePage.php"><a href="welcome.php" style="text-decoration: none; color: black"><h1>RENT CAR</h1></a></form>
            <hr>
            <h1 class="h3 mb-3 fw-normal">Iniciar Sessão</h1>
            <form action="" method="POST">
                <div class="form-floating">
                    <input type="text" name="Email" class="form-control" id="floatingEmail" placeholder="name@example.com" required="">
                    <label for="floatingEmail">Email</label>
                </div>
                <div class="form-floating">
                    <input type="password" name="PassWord" class="form-control" id="floatingPassword" placeholder="Password" required="">
                    <label for="floatingPassword">Password</label>
                </div>

                <button name="login" value="login" class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
            </form>
            <hr>
            <a class="btn btn-secondary" href="regist.php">Registar novo utilizador</a>                
            <hr>
            <div class="container"><?= $html ?></div>

        </main>
    </body>
</html>
<style>
    
    .body{
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: greenyellow;
    
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        
    }
    .form-signin{

       
        
        width: 100%;
        max-width: 400px;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
</style>