<?php 
if (isset($_GET['id'])) {
    $idCliente = $_GET['id'];
    
    $sql = "SELECT * FROM Cliente WHERE idCliente = :idCliente";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = filter_input(INPUT_POST, 'inputnome', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'inputemail', FILTER_VALIDATE_EMAIL);
            $contacto = filter_input(INPUT_POST, 'inputcontacto', FILTER_SANITIZE_NUMBER_INT);
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
            $stm->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            
            $stm->execute();
            header('Location: ?m=cliente&a=clientes');
            exit;
        }
?>

<form method="post">
    <div class="form-group">
        <label for="inputnome">Nome</label>
        <input type="text" class="form-control" id="inputnome" name="inputnome" value="<?= htmlspecialchars($row['NomeCliente']) ?>">
    </div>
    <div class="form-group">
        <label for="inputemail">Email</label>
        <input type="email" class="form-control" id="inputemail" name="inputemail" value="<?= htmlspecialchars($row['Email']) ?>">
    </div>
    <div class="form-group">
        <label for="inputcontacto">Contacto Telefónico</label>
        <input type="tel" class="form-control" id="inputcontacto" name="inputcontacto" value="<?= htmlspecialchars($row['TeleCliente']) ?>">
    </div>
    <div class="form-group">
        <?php
        $perms = $row['IsAdmin'] ? "Administrador" : "Cliente";
        ?>
        <label for="inputperms">Permissões</label>
        <input type="text" class="form-control" id="inputperms" name="inputperms" value="<?= htmlspecialchars($perms) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>

</form>

<?php
    } else {
        echo '<div class="alert alert-danger">ID do cliente não encontrado.</div>';
    }
}
?>
