<div class="container" style="margin-left: auto; margin-right: auto; margin-top:5vh;" >
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nome</th> 
                        <th scope="col">Email</th>
                        <th scope="col">Contacto</th>
                        <th scope="col">Acesso</th>
                        <th scope="col">Edição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sql = "SELECT * FROM Cliente";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result && count($result) > 0) {
                            foreach ($result as $row) {
                    ?>
                    <tr>
                        <th scope="row"><?= $row['idCliente']?></th>
                        <td><?= $row['NomeCliente']?></td>
                        <td><?= $row['Email']?></td>
                        <td><?= $row['TeleCliente']?></td>
                        <td><?php $admin = ""; if($row['IsAdmin'] == 1){$admin = "Administrador";}else{$admin="Cliente";} echo"$admin";?></td>
                        
                        <td>
                            <a type="button" class="btn btn-primary" href="?m=cliente&a=editarCliente&id=<?= urlencode($row['idCliente'])?>">Editar</a>
                            <a type="button" class="btn btn-danger" href="?m=admin&a=delCliente&id=<?= urlencode($row['idCliente'])?>">Apagar</a>
                           
                        </td>
                    </tr>
                    <?php
                    }
                    } else {
                    echo '<div class="no-results">Sem Clientes no Sistema( </div>';
                    }
                    ?>
                    
                </tbody>
            </table>
        </div>
    </div>
    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
</div>