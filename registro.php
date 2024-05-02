<?php

//Verifciar si hay datos por el metodo post
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include("conexion.php");
    //Array para guardar los errores
    $errores= array();
    //Variable para saber si se ha registrado correctamente
    $succes = false;

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
    //Validar si el nombre solo contener letras, espacios, guiones y apóstrofes
    if (!preg_match("/^[a-zA-Z-' ]*$/", $nombre)) {
        $errores['nombre'] = "El nombre solo puede contener letras, espacios, guiones y apóstrofes";
    }
    if (empty($apellidos)){
        $errores['apellidos']= "Los apellidos son obligatorios";
   }
    //Validar si apellidos solo contener letras, espacios, guiones y apóstrofes
    if (!preg_match("/^[a-zA-Z-' ]*$/", $apellidos)) {
    $errores['apellidos'] = "Los apellidos solo pueden contener letras, espacios, guiones y apóstrofes";
    } 
  
    // Se remueven todos los caracteres ilegales de email antes de validar
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

   //Validación de correo
   if (empty($email)) {
    $errores['email'] = "El correo es obligatorio";

    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //Verificar que el correo sea válido (formato) 
        $errores['email'] = "El correo no es válido";
    } 

    //******Inicia validación de password existente en bd*****
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $email = $_POST['email'];
        // Consulta para ver si usuario ya existe en la base de datos
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        // Si el resultado es verdadero, el usuario ya existe y se muestra un mensaje de error
        if ($resultado) {
            $errores['email'] = "Ya existe un usuario con ese correo";
        }
    
    } catch(PDOException $e) {
        echo "Error de conexión: ". $e->getMessage();
    }

    //******Termina validación de password existente en bd*****


    //validar que la contraseña no está vacía
    if (empty($password)) {
        $errores['password'] = "La contraseña es obligatoria";
    }

    //Validar que la contraseña tenga;
    /*(?=.*[A-Z]) - Al menos una mayuscula
    (?=.*[0-9]) - Al menos un número
    (?=.*[@$!%*?&]) - Al menos un caracter especial
    [A-Za-z0-9@$!%*?&]{8,} - Al menos de 8 caracteres*/
    if (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z0-9@$!%*?&]{8,}$/", $password)) {
        $errores['password'] = "La contraseña debe tener al menos 8 caracteres, incluir al menos una letra mayúscula, un número y un carácter especial";
    }

    //validar que la confirmación de contraseña no está vacía o que la confirmación coincida con password
    if (empty($confirmarPassword)) { 
        $errores['confirmarPassword'] = "La confirmación de contraseña es obligatoria";
    }elseif ($password!=$confirmarPassword) {
        $errores['confirmarPassword'] = "Las contraseñas no coinciden";
    }


    //Imprimir errores en pantalla si los hay
   foreach($errores as $error){
        //echo "<li>$error</li>";
        $error;
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

            //Encriptar la contraseña y se guarda en nueva variable
            $newPassword = password_hash($password, PASSWORD_DEFAULT);

            //Preparar la consulta
            $resultado=$pdo->prepare($sql);
            //Ejecutar la consulta tomano los datos recibidos del formulario
            $resultado->execute(array(
                ':nombre'=>$nombre, 
                ':apellidos'=>$apellidos,
                ':email'=>$email,
                ':password'=>$newPassword //se envia el valor de la nueva variable con la contraseña encriptada
            ));
            //la variable para mensaje de éxito se actualiza a true después de insertar el usuario
            $succes = true;
            //Mensaje de confirmación de creación que activa Sweet Alert 2
            //Llama a código de templates/header.php
            $mensaje="Se registro el usuario correctamente";
    
            //Redireccionar al login
            header("Location:login.php?mensaje=".$mensaje);
            //header("Refresh: 2; url=login.php");
    
        }catch(Exception $ex){
            echo "Error de conexión:".$ex->getMessage();
        }
   }else {
    //echo  "<a href='registro.php'>Regresar a formulario</>";
    //La variable para mensaje de exito se actualiza a false si no se pudo insertar
    $succes=false;
   }

}
?>


<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <!-- cdn JQuery v.3.7.1-->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- cdn DataTables v.1.12.1 -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
        <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
        <!-- cdn para Sweet Alert 2, alertas de acciones -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <header>
        </header>       

        <main class="container">
                <!--Inicia código de mensaje de alerta cuando se borra o crea registro-->
                <!--Si hay algo en el métod get-->
                <?php if(isset($_GET['mensaje'])){ ?>
                    <!--se corre el mensaje de eliminado en línea 19-->
                    <script>
                        Swal.fire({icon:"success", title:"<?php echo $_GET['mensaje'];?>"});
                    </script>
                <?php } ?>
                <!--Termina código de mensaje de alerta cuando se borra registro-->

    <!--
    <form action="registro.php" id="formularioRegistro" method="post">
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
        <button type="submit">Registrar</button>
        <a href="login.html">Login</a>
    </form>
    -->

        <!--Nuevo look inicia-->
    <h2>Registrarse</h2>
    <div class="card">
        <div class="card-header">Datos del usuario</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->
            <?php if(isset($error)) { ?>
                <?php foreach($errores as $error){ ?>
                <div class="alert alert-danger" role="alert">
                    <strong><?php echo $error;?></strong>
                </div>
                <?php }?>
            <?php }?>
            <!--Fin envio de mensaje de error-->
            <form action="registro.php" id="formularioRegistro" method="post">
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
                <button type="submit" class="btn btn-success">Registrar</button>
                <a name="" id="" class="btn btn-primary" href="login.php" role="button">Login</a>
            </form>

        </div>
        <div class="card-footer text-muted"></div>
    </div>
    <!--Nuevo look termina-->

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

</main>
<footer>
  <p>Xpert Tours 2024</p>
</footer>
<!-- Bootstrap JavaScript Libraries -->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
></script>

<!--Función para llamar Data Tables-->
<script>
  $(document).ready(function(){
    $("#tabla_id").DataTable({
      "pageLength":3,
      lengthMenu:[
        [3,5,10,25,50],
        [3,5,10,25,50]
      ],
      "language": {
          //No carga modulo de lengua, genera error, descomentar cuando haya solución
            //"url":"//cdn.datatables.net/plug-ins/2.0.5/i18n/es-MX.json"
        }
    });
  });
</script>

</body>
</html>