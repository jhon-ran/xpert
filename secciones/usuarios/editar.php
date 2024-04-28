<?php include("../../bd.php");
include("../../conexion.php");

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


     //Recolecta datos de método POST: Validación: que exista la información enviada, lo vamos a igualar a ese valor,
    //de lo contratrio lo deja en blanco
    $txtID = (isset($_POST["txtID"])? $_POST["txtID"]:"");
    $nombre = (isset($_POST["nombre"])? $_POST["nombre"]:"");
    $apellidos = (isset($_POST["apellidos"])? $_POST["apellidos"]:"");
    $email = (isset($_POST["email"])? $_POST["email"]:"");
    $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");
    $password = (isset($_POST["password"])? $_POST["password"]:"");
    

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
    //Redirecionar a la lista de puestos
    header("Location:index.php");
}

//******Termina código para modificar registro******

?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>
    <!--Nuevo look inicia-->
    <h2>Editar usuario</h2>
    <div class="card">
        <div class="card-header">Datos del usuario</div>
        <div class="card-body">
            <form action="editar.php" id="editarUsuarios" method="post">
            <div class="mb-3">
                    <label for="txtID" class="form-label">ID</label>
                    <input type="text" class="form-control" value ="<?php echo $txtID;?>" name="txtID" id="txtID" aria-describedby="helpId" readonly placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" value="<?php echo $nombre;?>" name="nombre" id="nombre" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" value = "<?php echo $apellidos;?>" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo</label>
                    <input type="email" class="form-control" name="email" id="email" value = "<?php echo $email;?>" aria-describedby="helpId" placeholder=""/>
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
                    <label for="nombre" class="form-label">Tipo de usuario actual</label>
                    <input type="text" class="form-control" name="tipoUsuario" id="tipoUsuario" value = "<?php echo $tipo;?>" readonly aria-describedby="helpId" placeholder=""/>
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