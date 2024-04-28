<?php

//Descomentar para servidor remoto
$servidor = "127.0.0.1:3306";
$baseDatos = "u878617270_bd_xpert";
$usuario = "u878617270_superbad";
$contrasena = "Romulo_xpert8";

//En caso de que no funcione el servidor remoto, descomentar para servidor local
$servidor = "localhost";
$baseDatos="bd_xpert";
$usuario="root";
$contrasena="";

try{
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
}catch(Exception $ex){
    echo $ex->getMessage();
}

?>