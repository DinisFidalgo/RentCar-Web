<?php

if(isset($_GET['id'])){
$idViatura = $_GET['id'];

        $sql ="SELECT Viatura.MatriculaViatura, Viatura.Estado_Reserva, Specs.TipoViatura, Specs.MotorViatura, 
                               Specs.CombustivelViatura, Specs.CorViatura, Specs.AnoViatura, Fabricante.NomeModelo, 
                               Fabricante.NomeFabricante, Viatura.idSpec, Viatura.Fabricante_idFabricante
                        FROM Viatura
                        INNER JOIN Specs ON Viatura.idSpec = Specs.idSpec
                        INNER JOIN Fabricante ON Viatura.Fabricante_idFabricante = Fabricante.idModelo
                        WHERE Viatura.MatriculaViatura = :idViatura";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idViatura', $idViatura, PDO::PARAM_STR);
        $stmt->execute();
         if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             
             
             
             $guardar = filter_input(INPUT_POST, 'guardar');
             if ($guardar) {
            $matricula = filter_input(INPUT_POST, 'inputMatricula');
            $fabricante = filter_input(INPUT_POST, 'inputFabricante');
            $modelo = filter_input(INPUT_POST, 'inputModelo');
            $categoria = filter_input(INPUT_POST, 'inputCategoria');
            $motor = filter_input(INPUT_POST, 'inputMotor');
            $fuel = filter_input(INPUT_POST, 'inputFuel');
            $cor = filter_input(INPUT_POST, 'inputCor');
            $ano = filter_input(INPUT_POST, 'inputAno');
            $idfabricante = filter_input(INPUT_POST, 'idfabricante');
            $idSpec = filter_input(INPUT_POST, 'idspec');

            $fmSql = "UPDATE Fabricante SET NomeModelo = :modelo, NomeFabricante = :fabricante WHERE idModelo = :idfabricante";
            $stmt = $pdo->prepare($fmSql);
            $stmt->bindParam(':modelo', $modelo, PDO::PARAM_STR);
            $stmt->bindParam(':fabricante', $fabricante, PDO::PARAM_STR);
            $stmt->bindParam(':idfabricante', $idfabricante, PDO::PARAM_INT);
            $stmt->execute();

            $specSql = "UPDATE Specs SET TipoViatura = :categoria, MotorViatura = :motor, CombustivelViatura = :fuel, CorViatura = :cor, AnoViatura = :ano WHERE idSpec = :idSpec";
            $stm = $pdo->prepare($specSql);
            $stm->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $stm->bindParam(':motor', $motor, PDO::PARAM_STR);
            $stm->bindParam(':fuel', $fuel, PDO::PARAM_STR);
            $stm->bindParam(':cor', $cor, PDO::PARAM_STR);
            $stm->bindParam(':ano', $ano, PDO::PARAM_INT);
            $stm->bindParam(':idSpec', $idSpec, PDO::PARAM_INT);
            $stm->execute();
            
            
            header('Refresh:0 ; URL= ?m=viaturas&a=lista');
        }
        ?>
<form method="post" action=""> 
    <input type="hidden" class="form-control" id="idspec" name="idspec" value="<?= $row['idSpec'] ?>">
    <input type="hidden" class="form-control" id="idfabricante" name="idfabricante" value="<?= $row['Fabricante_idFabricante'] ?>">

    <div class="form-group">
        <label for="exampleInputEmail1">Matricula</label>
        <input type="text" class="form-control" id="inputMatricula" name="inputMatricula" aria-describedby="emailHelp" placeholder="Matricula" value="<?= $idViatura?>" disabled>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Fabricante</label>
        <input type="text" class="form-control" id="inputFabricante" name="inputFabricante" placeholder="Fabricante" value="<?=$row['NomeFabricante']?>">
    </div>
     <div class="form-group">
        <label for="exampleInputPassword1">Modelo</label>
        <input type="text" class="form-control" id="inputModelo" name="inputModelo" placeholder="Modelo" value="<?= $row['NomeModelo']?>">
    </div>
    <div class="form-group">
                <label for="exampleInputPassword1">Categoria</label>
                <input type="text" class="form-control" id="inputCategoria" name="inputCategoria" placeholder="Modelo" value="<?= $row['TipoViatura'] ?>">
            </div>
    <div class="form-group">
                <label for="exampleInputPassword1">Motor</label>
                <input type="text" class="form-control" id="inputMotor" name="inputMotor" placeholder="Modelo" value="<?= $row['MotorViatura'] ?>">
            </div>
    <div class="form-group">
                <label for="exampleInputPassword1">Combustivel</label>
                <input type="text" class="form-control" id="inputFuel" name="inputFuel" placeholder="Modelo" value="<?= $row['CombustivelViatura'] ?>">
            </div>
    <div class="form-group">
                <label for="exampleInputPassword1">Cor</label>
                <input type="text" class="form-control" id="inputCor" name="inputCor" placeholder="Modelo" value="<?= $row['CorViatura'] ?>">
            </div>
    <div class="form-group">
                <label for="exampleInputPassword1">Ano</label>
                <input type="text" class="form-control" id="inputAno" name="inputAno" placeholder="Modelo" value="<?= $row['AnoViatura'] ?>">
            </div>
    <div class="form-group">
        <?php
        
        if($row['Estado_Reserva']==0){
            $reserva = "Sem Reserva";
        }else{
            $reserva = "Reservado";
        } 
        
        ?>
                <label for="exampleInputPassword1">Reserva</label>
                <input type="text" class="form-control" id="inputReserva" name="inputReserva" placeholder="Modelo" value="<?= $reserva ?>" disabled>
            </div>
    <button class="btn btn-primary" name="guardar" type="submit" value="guardar">Guardar</button>
    <a href="javascript:history.back()" class="btn btn-secondary">Voltar</a>
</form>

<?php

} else {
    echo '<div class="alert alert-danger">ID da viatura não encontrado.</div>';
}}
?>