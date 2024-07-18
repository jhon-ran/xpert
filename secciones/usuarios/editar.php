<?php include("../../bd.php");
include("../../conexion.php");
//se inicializa variable de sesión
session_start();

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
    //Si esta variable existe, se asigna ese valor, de lo contrario se queda
    $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
    //Se prepara sentencia para editar dato seleccionado (id)
    $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE id=:id");
    //Asignar los valores que vienen del método GET (id seleccionado por params)
    $sentencia->bindParam(":id",$txtID);
    //Se ejecuta la sentencia con el valor asignado para borrar
    $sentencia->execute();
    //Popular el formulario con los valores de 1 registro
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    //Asignar los valores que vienen del formulario (POST)
    $nombre = $registro["nombre"];
    $apellidos = $registro["apellidos"];
    $email = $registro["email"];
    $tipo = $registro["tipo"];
    //print_r($restricciones);
}
//******Termina código para recibir registro******

//******Inicia código para modificar registro******
//Verifciar si hay datos por el metodo post
if($_POST){
    //Descomentar la línea de abajo si se quiere verificar que los datos por POST están llegando
    //print_r($_POST);
    //Array para guardar los errores
    $errores= array();

    //Recolecta datos de método POST: Validación: que exista la información enviada, se iguala a ese valor,
    //de lo contratrio se deja en blanco
    $txtID = (isset($_POST["txtID"])? $_POST["txtID"]:"");
    $nombre = (isset($_POST["nombre"])? $_POST["nombre"]:"");
    $apellidos = (isset($_POST["apellidos"])? $_POST["apellidos"]:"");
    $email = (isset($_POST["email"])? $_POST["email"]:"");
    $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");
    $password = (isset($_POST["password"])? $_POST["password"]:"");
    $confirmarPassword = (isset($_POST["confirmarPassword"])? $_POST["confirmarPassword"]:"");
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
        $errores['apellidos'] = "Los apellidos solo puede contener letras, espacios, guiones y apóstrofes";
    }
    if (empty($tipo)){
    $errores['tipo']= "El tipo de usuario es obligatorio";
}
    // Se remueven todos los caracteres ilegales de email antes de validar
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    //Validación de correo
    if (empty($email)) {
    $errores['email'] = "El correo es obligatorio";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //Verificar que el correo sea válido (formato) 
        $errores['email'] = "El formato del correo no es válido";
    } 

    //******Inicia validación de email existente en bd*****
    try {
        $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $email = $_POST['email'];
        $id = $_POST['txtID'];
        // Consulta para ver si otro usuario usa el mismo email en la base de datos
        $sql = "SELECT * FROM usuarios WHERE email = :email AND id != :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        // Si el resultado es verdadero, el usuario ya existe y se muestra un mensaje de error
        if ($resultado) {
            $errores['email'] = "Ya existe otro usuario con ese correo";
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
    /*if (empty($tipo)) {
        $errores['tipo'] = "Debe seleccionar un tipo de usuario";
    }
    */

    //Imprimir errores en pantalla si los hay
   foreach($errores as $error){
        $error;
   }


       //Si no hay errores (array de errores vacio)
   if(empty($errores)){
    //Conexion a la base de datos
        try{
            //Preparar la inseción de los datos enviados por POST
            $sentencia = $conexion->prepare("UPDATE usuarios SET nombre=:nombre,
            apellidos=:apellidos,email=:email,
            password=:password,tipo=:tipo WHERE id=:id");

            //Encriptar la contraseña y se guarda en nueva variable
            $newPassword = password_hash($password, PASSWORD_DEFAULT);

            //Asignar los valores que vienen del formulario (POST)
            $sentencia->bindParam(":nombre",$nombre);
            $sentencia->bindParam(":apellidos",$apellidos);
            $sentencia->bindParam(":email",$email);
            $sentencia->bindParam(":password",$newPassword);
            $sentencia->bindParam(":tipo",$tipo);
            $sentencia->bindParam(":id",$txtID);
            $sentencia->execute();
            //Mensaje de confirmación de edición que activa Sweet Alert 2
            //Llama a código de templates/header.php
            $mensaje="Registro editado";
            //Redirecionar después de editar a la lista de puestos
            header("Location:index.php?mensaje=".$mensaje);
            //******Termina código para modificar registro******
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
            <h1>Editar usuario</h1>
    </header>

    <div class="row">
    <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Datos del usuario</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="editar.php" id="editarUsuarios" method="post">
            <div class="mb-3">
                    <label for="txtID" class="form-label">ID</label>
                    <input type="text" class="form-control" value ="<?php echo $txtID;?>" name="txtID" id="txtID" aria-describedby="helpId" readonly placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" value="<?php echo $nombre;?>" name="nombre" id="nombre" aria-describedby="helpId" placeholder="" required/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['nombre'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['nombre']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" value = "<?php echo $apellidos;?>" aria-describedby="helpId" placeholder="" required/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['apellidos'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['apellidos']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" id="email" value = "<?php echo $email;?>" aria-describedby="helpId" placeholder="" required/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['email'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['email']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input
                        type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ingrese su contraseña o una nueva" required/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['password'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['password']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="confirmarPassword" class="form-label">Confirmar contraseña</label>
                    <input
                        type="password" class="form-control" name="confirmarPassword" id="confirmarPassword" aria-describedby="helpId" placeholder="Repita la contraseña"/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['confirmarPassword'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['confirmarPassword']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Tipo de usuario actual</label>
                    <input type="text" class="form-control" name="tipoUsuario" id="tipoUsuario" value = "<?php echo $tipo;?>" readonly aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de usuario</label>
                    <select class="form-select form-select-sm" name="tipo" id="tipo">
                        <option value="" selected>Seleccione una opción si quiere cambiarlo</option>
                        <option value="admin">Administrador</option>
                        <option value="cliente">Cliente</option>
                        <option value="ventas">Ventas</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Editar</button>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </form>
        </div>
        <!--<div class="card-footer text-muted"></div>-->
        </div>
    </div>
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


<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>