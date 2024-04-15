<?php

//Verifciar si hay datos por el metodo post
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include("conexion.php");
    //Array para guardar los errores
    $errores= array();
    //Imprimir los datos del formulario en pantalla
    //print_r($_POST);

    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $nombre =(isset($_POST["nombre"]))? $_POST["nombre"]:null;
    $apellidos =(isset($_POST["apellidos"]))? $_POST["apellidos"]:null;
    $email =(isset($_POST["email"]))? $_POST["email"]:null;
    $password =(isset($_POST["password"]))? $_POST["password"]:null;
    $confirmarPassword =(isset($_POST["confirmarPassword"]))? $_POST["confirmarPassword"]:null;

    //Si los datos están, se llena el array con los mensajes de errores
    if (empty($nombre)){
         $errores['nombre']= "El nombre es obligatorio";
    }
    if (empty($apellidos)){
        $errores['apellidos']= "El apellido es obligatorio";
   } 
   //print_r($errores);

   //Validación de correo
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
    //validar que la confirmación de contraseña no está vacía o que la confirmación coincida con password
    if (empty($confirmarPassword)) { 
        $errores['confirmarPassword'] = "La confirmación de contraseña es obligatoria";
    }elseif ($password!=$confirmarPassword) {
        $errores['confirmarPassword'] = "Las contraseñas no coinciden";
    }

}
    //Imprimir errores en pantalla si los hay
   foreach($errores as $error){
    echo "<li>$error</li>";
   }

   //Si no hay errores (array de errores vacio)
   if(empty($errores)){
        //Conexion a la base de datos
        try{
            $pdo = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
            //Preparar la consulta
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//para que PDO maneje los errores de manera automática
            $sql = "INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`) 
            VALUES (NULL, :nombre, :apellidos, :email, :password)";

            //Encriptar la contraseña
            $newPassword = password_hash($password, PASSWORD_DEFAULT);

            //Preparar la consulta
            $resultado=$pdo->prepare($sql);
            //Ejecutar la consulta tomano los datos recibidos del formulario
            $resultado->execute(array(
                ':nombre'=>$nombre, 
                ':apellidos'=>$apellidos,
                ':email'=>$email,
                ':password'=>$newPassword
            ));
            //Redireccionar al login después de inscribirse
            header("Location:login.html");
        
    
            echo "Usuario registrado";
    
        }catch(Exception $ex){
            echo "Error de conexión:".$ex->getMessage();
        }
   }else {
    echo  "<a href='registro.html'>Regresar a formulario</>";
   }

?>