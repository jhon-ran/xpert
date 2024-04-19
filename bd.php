<?php

$servidor = "localhost";
$baseDatos = "bd_xpert";
$usuario = "root";
$contrasena = "";

try{
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
}catch(Exception $ex){
    echo $ex->getMessage();
}

?>