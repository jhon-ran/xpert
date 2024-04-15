<?php 
//Verifciar si hay datos por el metodo post
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    include("conexion.php");
    //print_r($_POST);
    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $email=(isset($_POST['email']))?htmlspecialchars($_POST['email']):null;
    $password=(isset($_POST['password']))?$_POST['password']:null;

    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
        //para que PDO maneje los errores de manera automática
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Consultar la base de datos
        $sql ="SELECT * FROM usuarios WHERE email=:email";
        //Preparar la consulta
        $sentencia = $pdo->prepare($sql);
        //Ejecutar la consulta
        $sentencia->execute(['email'=>$email]);
        //Obtener los datos
        $usuarios = $sentencia->fetch(PDO::FETCH_ASSOC);
        print_r($usuarios);


    } catch (PDOException $ex) {
        echo "Error de conexión:".$ex->getMessage();
    }

}



?>