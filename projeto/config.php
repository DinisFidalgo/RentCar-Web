<?php

/* * * * * * * * * * * * * * *
 * C O N F I G U R A Ç Ã O
 * 
 */
// Autor
define('AUTHOR', 'PREENCHER COM O SEU NOME');
define('ANO_LETIVO', 'PREENCHER COM O ANO LETIVO');



/* * * * * * * * * * * * * * *
 * B A S E   D E   D A D O S
 */
# ALTERAR GRUPO
$guru = '02';
$dsg_dbo = [
    'host' => 'mysql-sa.mgmt.ua.pt',
    'port' => '3306',
    'charset' => 'utf8',
    'dbname' => 'esan-dsg' . $guru,
    'username' => 'esan-dsg' . $guru . '-dbo',
    # COLOCAR PASSWORD DBO
    'password' => 'woJ7VLpGd4FMqEmB'
];
$dsg_web = [
    'host' => 'mysql-sa.mgmt.ua.pt',
    'port' => '3306',
    'charset' => 'utf8',
    'dbname' => 'esan-dsg' . $guru,
    'username' => 'esan-dsg' . $guru . '-web',
    # COLOCAR PASSWORD WEB
    'password' => 'YLliZyrstzTvVxA0'
];

/** @var Array $db['host','port','charset','dbname','username','password'] */
# Descomentar utilizador DBO ou WEB
$db = $dsg_dbo;
#$db = $dsg_web;



/* * * * * * * * * *
 * D E B U G
 */
define('DEBUG', true);

if (defined('DEBUG') && DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $_DEBUG = '';
}
