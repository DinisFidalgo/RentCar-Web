<?php
session_start();

$html = "";

require_once './config.php';
require_once './core.php';

$module = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$action = filter_input(INPUT_GET, 'a', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (!file_exists("./mod/$module/$action.php")) {
    $module = 'home';
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php ");
    exit();
}
?>


<!doctype html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentCar - Página Inicial</title>
    <link rel="icon" type="image/x-icon" href="media/ToyotaAE86.jpg">
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
        .card-img-top {
            width: 100%;
            height: 200px; 
            object-fit: cover; 
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">RentCar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?m=tendencias&a=tendencias">Tendências</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?m=viaturas&a=marcas">Marcas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?m=ofertas&a=ofertas">Ofertas</a>
                    </li>
                    <li><?= $html ?></li>
                    <?php if (is_admin()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?m=admin&a=menuAdmin">Administração</a>
                        </li>
                    <?php } ?>  

                </ul>

                <a class="nav-link" href="?m=cliente&a=areaCliente">  Área de Cliente  </a>


                &nbsp&nbsp&nbspBem-Vindo <?= $_SESSION['NomeCliente'] ?>! &nbsp&nbsp&nbsp&nbsp

                <form class="d-flex" role="search" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Pesquisar" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit" style="color: whitesmoke">Pesquisar</button>
                </form>
                <form class="d-flex ms-3" action="logout.php" method="post">
                    <button class="btn btn-danger" type="submit" id="btnLogout">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <?php
    if ($module != 'home') {
        debug("Loading: $module/$action.php");
        $pdo = connectDB($db);
        require_once "./mod/$module/$action.php";
    } else {
        ?>

        <div class="container mt-4">



            <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    require_once './config.php';
    require_once './core.php';

    try {
        $pdo = connectDB($db);

        $sql = "SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, 
                               Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, 
                               Fabricante.NomeFabricante
                        FROM Viatura
                        INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
                        INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo
                       ";

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
                                    <img src="./media/<?= htmlspecialchars($row['NomeFabricante']) ?><?= htmlspecialchars($row['NomeModelo']) ?>.jpg" 
                                         class="card-img-top" alt="Imagem do carro">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($row['NomeFabricante'] . ' ' . $row['NomeModelo']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($row['TipoViatura']) ?></p>
                                    </div>
                                    <div class="card-body">
                                        <a href="?m=detalhes&a=detalhes&id=<?= urlencode($row['MatriculaViatura']) ?>" class="btn btn-primary">Mais detalhes</a>
                                        <a href="?m=reservas&a=reservas&id=<?= urlencode($row['MatriculaViatura']) ?>" 
                                                           class="btn btn-success <?= $row['Estado_Reserva'] == 1 ? 'disabled' : '' ?>" 
                                                           <?= $row['Estado_Reserva'] == 1 ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                                                            Reservar
                                                        </a>
                                        <a href="?m=reservas&a=disponibilidade&id=<?= urlencode($row['MatriculaViatura']) ?>" class="btn btn-warning">Disponibilidade</a>
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

            <?php } ?>

</body>
</html>
