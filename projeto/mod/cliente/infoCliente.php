<?php
if (isset($_SESSION['user_id'])) {
    $sql = "SELECT * FROM Cliente WHERE idCliente = :idCliente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idCliente', $_SESSION['user_id'], PDO::PARAM_INT); 
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
            $nome = filter_input(INPUT_POST, 'inputNomeCliente', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'inputEmail', FILTER_VALIDATE_EMAIL);
            $contacto = filter_input(INPUT_POST, 'inputContact', FILTER_SANITIZE_NUMBER_INT);
            $acesso = filter_input(INPUT_POST, 'inputperms', FILTER_SANITIZE_STRING);

            $isadmin = ($acesso === "Administrador" || $acesso === "Admin") ? 1 : 0;

            $updateClient = "UPDATE Cliente 
                             SET NomeCliente = :nome, Email = :email, TeleCliente = :contacto, IsAdmin = :acesso 
                             WHERE idCliente = :idCliente";

            $stm = $pdo->prepare($updateClient);
            $stm->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stm->bindParam(':email', $email, PDO::PARAM_STR);
            $stm->bindParam(':contacto', $contacto, PDO::PARAM_INT);
            $stm->bindParam(':acesso', $isadmin, PDO::PARAM_INT);
            $stm->bindParam(':idCliente', $_SESSION['user_id'], PDO::PARAM_INT); 

            $stm->execute();

            header('Refresh:0; URL=?m=cliente&a=areaCliente');
            exit;
        }
        ?>

        <form method="post" action="">
            <br>
            <div class="form-group">
                <label for="inputNomeCliente">Nome</label>
                <br>
                <input type="text" class="form-control" id="inputNomeCliente" name="inputNomeCliente" placeholder="Nome" value="<?= htmlspecialchars($row['NomeCliente']) ?>">
            </div>
            <br>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <br>
                <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email" value="<?= htmlspecialchars($row['Email']) ?>">
            </div>
            <br>
            <div class="form-group">
                <label for="inputContact">Contacto</label>
                <br>
                <input type="text" class="form-control" id="inputContact" name="inputContact" placeholder="Contacto" value="<?= htmlspecialchars($row['TeleCliente']) ?>">
            </div>
            <div>
                <br>
                <button class="btn btn-primary" name="guardar" type="submit" value="guardar">Guardar</button>
                <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
        <?php
    }
}
?>
