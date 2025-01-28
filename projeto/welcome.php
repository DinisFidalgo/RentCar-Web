<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="icon" type="image/x-icon" href="media/ToyotaAE86.jpg">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" 
          integrity="sha384-QWTKID7jM6P9cR6N2vZlKFdtiW3W8WZQjz2pH3g4n4q6I7ySxK6+XyI8UzA1kzxG" 
          crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 400px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: bold;
        }

        .btn-custom {
            width: 100%;
            margin: 10px 0;
            font-size: 1.2rem;
            padding: 10px;
            font-weight: bold;
            border: none;
        }

        .btn-login {
            background-color: #28a745;
            color: white;
        }

        .btn-register {
            background-color: #007bff;
            color: white;
        }

        .btn-help {
            font-size: 1rem;
            text-decoration: none;
            color: #6c757d;
            margin-top: 20px;
            display: inline-block;
        }

        .btn-help:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> RentCar </h1>
        <h3> Bem-vindo! </h3>
        
        <form action="login.php">
            <button type="submit" class="btn btn-custom btn-login">Login</button>
        </form>

        <form action="regist.php">
            <button type="submit" class="btn btn-custom btn-register">Registar</button>
        </form>

        
    </div>
</body>
</html>
