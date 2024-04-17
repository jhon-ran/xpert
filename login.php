<?php 
//se inicializa variable de sesión
session_start();
//Verifciar si hay datos por el metodo post
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    include("conexion.php");
    //Array para guardar los errores
    $errores= array();

    //print_r($_POST);
    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $email=(isset($_POST['email']))?htmlspecialchars($_POST['email']):null;
    $password=(isset($_POST['password']))?$_POST['password']:null;



    //Validación que el correo no esté vacío y que tenga el formato requerido
   if (empty($email)) {
        $errores['email'] = "El email es obligatorio";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //Verificar que el correo sea válido (formato) 
        $errores['email'] = "El email no es válido";
    } 
    //validar que la contraseña no está vacía
    if (empty($password)) {
        $errores['password'] = "La contraseña es obligatoria";
    }

    //Si no hay errores (array de errores vacio)
    if(empty($errores)){
        //Verificar si el usuario (email) existe en BD
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
        //Obtener los datos de la consulta
        $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        print_r($usuarios);

        //Se inicializa la variable en flase para verificar si el password es correcto
        $login = false; 
        //Recorrer el arreglo de usuarios recuperados de la consulta
        foreach($usuarios as $usuario) {
            //Comparar password encriptado ingresado con el de la BD
            if (password_verify($password, $usuario['password'])) {
                //Guardar el id y nombre del usuario de la BD en las variables de sesión
                $_SESSION['usuario_id']= $usuario['id'];
                $_SESSION['usuario_nombre']= $usuario['nombre']." ".$usuario['apellidos'];

                $login = true;
            }
        }

        if($login){
            echo "Existe en la BD";
            //redirección al index
            header("Location: index.php");
        }else{
            echo "No existe en la BD";  
        }
         
    } catch (PDOException $ex) {
        echo "Error de conexión:".$ex->getMessage();
    }

}else{
    //Imprimir errores en pantalla si los hay
    foreach($errores as $error){
        echo "<li>$error</li>";
    }
    //Redireccionar a login
    echo  "<a href='login.php'>Regresar a login</>";
}
}



?>