<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php ");
    exit();
}
?>

<!doctype html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RentCar - Página Inicial</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" 
              integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <style>
            body {
                background-color: #f8f9fa; 
            }
           
            .navbar {
                --bs-bg-opacity: 1;
                background-color: rgba(var(--bs-secondary-rgb), var(--bs-bg-opacity)) !important;
            }
            #btnLogout {
                background-color: red;
                color: whitesmoke;
            }
            .no-results {
                margin-top: 20px;
                text-align: center;
                font-size: 18px;
                color: #6c757d;
            }
        </style>
      
    </head>
    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="../WelcomePage.php">RentCar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="mainHomePage.php">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="tendencias.php">Tendências</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="marcas.php">Marcas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Ofertas</a>
                        </li>                        
                    </ul>                    
                    <form class="d-flex" role="search" method="GET">
                        <input class="form-control me-2" type="search" name="search" placeholder="Pesquisar" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit" style="color: whitesmoke">Pesquisar</button>
                    </form>
                    <form class="d-flex ms-3" action="../logout.php" method="post">
                        <button class="btn btn-danger" type="submit" id="btnLogout">Logout</button>
                    </form>
                </div>
            </div>
        </nav>
       
        

        <div class="container mt-4">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                require_once '../config.php';
                require_once '../core.php';
                

                ini_set('display_errors', 1);
                error_reporting(E_ALL);

                try {
                    error_log("Iniciando conexão com o banco de dados...");
                    $pdo = connectDB($db);
                    error_log("Conexão com o banco de dados estabelecida.");

                    $sql = "SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, 
                               Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, 
                               Fabricante.NomeFabricante
                        FROM Viatura
                        INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
                        INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo
                        WHERE Viatura.Estado_Reserva = 0";

                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = '%' . $_GET['search'] . '%';
                        $sql .= " AND (Fabricante.NomeFabricante LIKE :search OR Fabricante.NomeModelo LIKE :search)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
                        $stmt->execute(); 
                        error_log("Pesquisa: " . $_GET['search']);
                    } else {
                        $stmt = $pdo->query($sql);
                    }

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    error_log("Encotrados: " . count($result));

                    if ($result && count($result) > 0) {
                        foreach ($result as $row) {
                            ?>
                            <div class="col">
                                <div class="card h-100">
                                    <img src="../media/<?= htmlspecialchars($row['NomeFabricante']) ?><?= htmlspecialchars($row['NomeModelo']) ?>.jpg" 
                                         class="card-img-top" alt="Imagem do carro">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($row['NomeFabricante'] . ' ' . $row['NomeModelo']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($row['TipoViatura']) ?></p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><?= $row['Estado_Reserva'] == 0 ? 'Disponível para Reserva' : 'Reservado' ?></li>
                                    </ul>
                                    <div class="card-body">
                                        <a href="../mod/detalhes/detalhes.php?id=<?= urlencode($row['MatriculaViatura']) ?>" class="btn btn-primary">Mais detalhes</a>
                                        <a href="../mod/reservas/reservas.php?id=<?= urlencode($row['MatriculaViatura']) ?>" class="btn btn-success">Reservar</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="no-results">Pesquisa sem resultados. :( </div>';
                    }
                } catch (PDOException $e) {
                    error_log("Erro no banco de dados: " . $e->getMessage());
                    echo '<div class="alert alert-danger">Erro ao carregar os dados. Por favor, tente mais tarde.</div>';
                }
                ?>
            </div>
        </div>
    </body>
</html>
