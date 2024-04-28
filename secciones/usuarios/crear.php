
<?php

//Verifciar si hay datos por el metodo post
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include("../../conexion.php");
    //Array para guardar los errores
    $errores= array();
    //Variable para saber si se ha registrado correctamente
    $succes = false;
    //Imprimir los datos del formulario en pantalla
    //print_r($_POST);

    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $nombre =(isset($_POST["nombre"]))? $_POST["nombre"]:null;
    $apellidos =(isset($_POST["apellidos"]))? $_POST["apellidos"]:null;
    $email =(isset($_POST["email"]))? $_POST["email"]:null;
    $password =(isset($_POST["password"]))? $_POST["password"]:null;
    $confirmarPassword =(isset($_POST["confirmarPassword"]))? $_POST["confirmarPassword"]:null;
    $tipo =(isset($_POST["tipo"]))? $_POST["tipo"]:null;

    //Si los datos están, se llena el array con los mensajes de errores
    if (empty($nombre)){
         $errores['nombre']= "El nombre es obligatorio";
    }
    if (empty($apellidos)){
        $errores['apellidos']= "El apellido es obligatorio";
   } 
   if (empty($tipo)){
    $errores['tipo']= "El tipo de usuario es obligatorio";
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
            $sql = "INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `tipo`) 
            VALUES (NULL, :nombre, :apellidos, :email, :password, :tipo)";

            //Encriptar la contraseña y se guarda en nueva variable
            $newPassword = password_hash($password, PASSWORD_DEFAULT);

            //Preparar la consulta
            $resultado=$pdo->prepare($sql);
            //Ejecutar la consulta tomano los datos recibidos del formulario
            $resultado->execute(array(
                ':nombre'=>$nombre, 
                ':apellidos'=>$apellidos,
                ':email'=>$email,
                ':password'=>$newPassword, //se envia el valor de la nueva variable con la contraseña encriptada
                ':tipo'=>$tipo
            ));
            //la variable para mensaje de éxito se actualiza a true después de insertar el usuario
            $succes = true;
    
            //Redireccionar al login después de 5 segundos de registrarse para ver mensaje de éxito
            //header("Refresh: 2; url=index.php");
            header("Location:index.php");
            
    
        }catch(Exception $ex){
            echo "Error de conexión:".$ex->getMessage();
        }
   }else {
    echo  "<a href='crear.php'>Regresar a formulario</>";
    //La variable para mensaje de exito se actualiza a false si no se pudo insertar
    $succes=false;
   }

}
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>
    <!--
    <h2>Registro</h2>
    <form action="crear.php" id="formularioRegistro" method="post">
        Nombre:
        <input type="text" name="nombre" id="nombre" required><br>
        Apellido: 
        <input type="text" name="apellidos" id="apellidos" required><br>
        Correo:
        <input type="email" name="email" id="email"><br>
        Contraseña: 
        <input type="password" name="password" id="password" required><br>
        Repetir contraseña: 
        <input type="password" name="confirmarPassword" id="confirmarPassword" required><br>
        <label for="tipo">Tipo de usuario:</label>
        <select name="tipo" id="tipo">
            <option value="admin">Administrador</option>
            <option value="cliente">Cliente</option>
            <option value="ventas">Ventas</option>
        </select>
        <br>
        <br>
        <button type="submit">Registrar</button>
        <a href="index.php">Cancelar</a>
    </form>
    -->

    <!--Nuevo look inicia-->
    <h2>Registrar usuarios</h2>
    <div class="card">
        <div class="card-header">Datos del usuario</div>
        <div class="card-body">
            <form action="crear.php" id="formularioRegistro" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" id="email"aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input
                        type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="confirmarPassword" class="form-label">Confirmar contraseña</label>
                    <input
                        type="password" class="form-control" name="confirmarPassword" id="confirmarPassword" aria-describedby="helpId" placeholder="Repita la contraseña"/>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de usuario</label>
                    <select class="form-select form-select-sm" name="tipo" id="tipo">
                        <option value="" selected>Seleccione una opción</option>
                        <option value="admin">Administrador</option>
                        <option value="cliente">Cliente</option>
                        <option value="ventas">Ventas</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Registrar</button>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </form>

        </div>
        <div class="card-footer text-muted"></div>
    </div>
    <!--Nuevo look termina-->

    <!--Script para mostrar un mensaje de éxito si se inserto el usuario correctamente-->
    <?php
        if (isset($succes)){ echo "<script>alert('Se ha creado el usuario exitosamente!');</script>";};
    ?>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>

<script>
    //Script para verificar que las contraseñas sean iguales antes de mandar los datos por POST 
        // Función para comparar las contraseñas
        
        function comparaPasswords() {
            var password = document.getElementById("password");
            var confirmarPassword = document.getElementById("confirmarPassword");    

            // Verificar que el password y la confirmación sean iguales
            if (password.value!== confirmarPassword.value) {
                // Mostrar el error
                    confirmarPassword.setCustomValidity("Las contraseñas no coinciden");
            } else {
                // Limpiar el mensaje de error
                confirmarPassword.setCustomValidity("");
            }
        }

        // Se llama la función cuando se intente enviar el POST
        document.getElementById("confirmarPassword").addEventListener("input", comparaPasswords);
    </script>