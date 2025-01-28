<?php

/**
 * Cria uma ligação a uma base de dados e devolve um objeto PDO com a ligação
 * @param Array $db
 * @return \PDO
 */
function connectDB($db) {
    debug('Base de Dados: ' . $db['dbname']. "\tUtilizador: " . $db['username']);
    try {
        $pdo = new PDO(
                'mysql:host=' . $db['host'] . '; ' . // string de ligação
                'port=' . $db['port'] . ';' . // string de ligação
                'charset=' . $db['charset'] . ';'. // string de ligação
                'dbname=' . $db['dbname'] . ';', // string de ligação
                $db['username'], // username
                $db['password']                         // password
        );
    } catch (PDOException $e) {
        die('Erro ao ligar ao servidor ' . $e->getMessage());
    }
    // Definir array associativo como default para fetch()
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Definir lançamento de exceção para erros PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}


/**
 * Verifica se o modo DEBUG está definido e ativo e escreve na consola do browser
 * @param mixed $info
 * @param sting $type [log, error, info]
 * @return bool
 */
function debug($info = '', $type='log'){
    if(defined('DEBUG') && DEBUG){
        echo "<script>console.$type(".json_encode($info).");</script>";
        return true;
    }
    return false;
}


/**
 * Verifica se o utilizador autenticado é admin
 * @return boolean
 */
function is_admin(){
    
      if (isset($_SESSION['user_id']) && $_SESSION['admin'] == true) {
        debug("Admin");
        return true;
    } else {
        debug("User");
        return false;
    }
}