
<?php
//se inicializa variable de sesión
session_start();
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
    //Validar si el nombre tiene más de 25 caracteres
    if (strlen($nombre) > 25) {
        $errores['nombre'] = "El nombre no puede tener más de 25 caracteres";
    }
    //Validar si el nombre tiene menos de 2 caracteres
    if (strlen($nombre) < 2) {
        $errores['nombre'] = "El nombre no puede tener menos de 2 caracteres";
    }
    //Validar si el nombre solo contener letras, espacios, guiones y apóstrofes
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\'\-]+$/', $nombre)) {
        $errores['nombre'] = "El nombre solo puede contener letras, espacios, guiones y apóstrofes";
    }
    if (empty($apellidos)){
        $errores['apellidos']= "Los apellidos son obligatorios";
   } 
    //Validar si los apellidos tienen más de 40 caracteres
    if (strlen($apellidos) > 40) {
    $errores['apellidos'] = "Los apellidos no pueden tener más de 40 caracteres";
    }
    //Validar si los apellidos tienen menos de 2 caracteres
    if (strlen($apellidos) < 2) {
        $errores['apellidos'] = "Los apellidos no pueden tener menos de 2 caracteres";
    }
    //Validar si apellidos solo contener letras, espacios, guiones y apóstrofes
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\'\-]+$/', $apellidos)) {
        $errores['apellidos'] = "Los apellidos solo pueden contener letras, espacios, guiones y apóstrofes";
    }
   /*if (empty($tipo)){
    $errores['tipo']= "El tipo de usuario es obligatorio";
    }*/
    // Se remueven todos los caracteres ilegales de email antes de validar
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

   //Validación de correo
   if (empty($email)) {
    $errores['email'] = "El correo es obligatorio";

    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //Verificar que el correo sea válido (formato) 
        $errores['email'] = "El correo no es válido";
    } 

        //******Inicia validación de email existente en bd*****
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
    
        //******Termina validación de email existente en bd*****


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
        $errores['password'] = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial";
    }
    
    //validar que la confirmación de contraseña no está vacía o que la confirmación coincida con password
    if (empty($confirmarPassword)) { 
        $errores['confirmarPassword'] = "La confirmación de contraseña es obligatoria";
    }elseif ($password!=$confirmarPassword) {
        $errores['confirmarPassword'] = "Las contraseñas no coinciden";
    }

    //validar que el tipo de usuario no está vacio (dropdown)
    if (empty($tipo)) {
        $errores['tipo'] = "Debe seleccionar un tipo de usuario";
    }


    //Imprimir errores en pantalla si los hay
   foreach($errores as $error){
        $error;
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
    
            //Mensaje de confirmación de creación que activa Sweet Alert 2
            //Llama a código de templates/header.php
            $mensaje="Registro creado";
            //Redirecionar después de crear a la lista de usuarios
            header("Location:index.php?mensaje=".$mensaje);
            
    
        }catch(Exception $ex){
            echo "Error de conexión:".$ex->getMessage();
        }
   }else {
    //La variable para mensaje de exito se actualiza a false si no se pudo insertar
    $succes=false;
   }

}
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    <!--Nuevo look inicia-->
    <header class="text-center">
            <h1>Registrar usuarios</h1>
    </header>
    

<div class="row my-2">
    <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Datos del usuario</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="crear.php" id="formularioRegistro" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" oninput="validateNombreUsuario()" aria-describedby="helpId" placeholder="Ingrese nombre(s)" value="<?php echo isset($nombre) ? $nombre : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarNombreUsuario.js -->
                    <span id="errorNombreUsuario" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['nombre'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['nombre']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" oninput="validateApellidosUsuario()" aria-describedby="helpId" placeholder="Ingrese apellido(s)" value="<?php echo isset($apellidos) ? $apellidos : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarNombreUsuario.js -->
                    <span id="errorApellidosUsuario" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['apellidos'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['apellidos']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" id="email" oninput="validateEmail()" aria-describedby="helpId" placeholder="Ingrese correo" value="<?php echo isset($email) ? $email : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarEmail.js -->
                    <span id="errorEmail" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['email'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['email']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input
                        type="password" class="form-control" name="password" id="password" oninput="validatePassword()" aria-describedby="helpId" placeholder="Ingrese contraseña" value="<?php echo isset($password) ? $password : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarPassword.js -->
                    <span id="errorPassword" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['password'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['password']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    <!--se llama a función para que el usuario pueda ver la contraseña que escribió-->
                    <input type="checkbox" onclick="mostrarPassword()"> <small>Mostrar</small>
                </div>
                <div class="mb-3">
                    <label for="confirmarPassword" class="form-label">Confirmar contraseña</label>
                    <input
                        type="password" class="form-control" name="confirmarPassword" id="confirmarPassword" oninput="validateConfirmarPassword()" aria-describedby="helpId" placeholder="Repita la contraseña" value="<?php echo isset($confirmarPassword) ? $confirmarPassword : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarPassword.js -->
                    <span id="errorConfirmarPassword" class="error"></span>     
                    <span id="" class="error"></span>     
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['confirmarPassword'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['confirmarPassword']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    <!--se llama a función para que el usuario pueda ver la contraseña que escribió-->
                    <input type="checkbox" onclick="mostrarConfirmarPassword()"> <small>Mostrar</small>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de usuario</label>
                    <select class="form-select form-select-sm" name="tipo" id="tipo" onclick="validateTipoUsuario()" required>
                        <option value="" selected>Seleccione una opción</option>
                        <option value="admin">Administrador</option>
                        <option value="cliente">Cliente</option>
                        <option value="ventas">Ventas</option>
                    </select>
                    <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                    <span id="errorTipo" class="error"></span>   
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['tipo'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['tipo']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <button type="submit" id="submitBtn" class="btn btn-success">Registrar</button>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </form>
            </div>
        </div>
    </div>
</div>
    <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>

<!-- Validaciones para campos de input de carpeta js-->
<script src="../../js/validarNombreUsuario.js"> </script>
<script src="../../js/validarApellidosUsuario.js"> </script>
<script src="../../js/validarEmail.js"> </script>
<script src="../../js/validarPassword.js"> </script>
<script src="../../js/validarConfirmarPassword.js"> </script>
<script src="../../js/validarTipoUsuario.js"> </script>

    <!--función para mostrar password-->
    <script>
        function mostrarPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        }
    </script>
    <!--función para mostrar confirmación de password-->
    <script>
        function mostrarConfirmarPassword() {
        var x = document.getElementById("confirmarPassword");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        }
    </script>