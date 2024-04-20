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
<h1>Editar Usuario</h1>

<form action="editar.php" id="editarUsuarios" method="post">

        ID:
        <input type="text" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" placeholder="ID"/><br>
        Nombre:
        <input type="text" name="nombre" id="nombre" value = "<?php echo $nombre;?>" required><br>
        Apellido: 
        <input type="text" name="apellidos" id="apellidos" value = "<?php echo $apellidos;?>" required><br>
        Correo:
        <input type="email" name="email" id="email" value = "<?php echo $email;?>"><br>
        Contraseña: 
        <input type="password" name="password" id="password" placeholder="Ingrese nueva contraseña" required><br>
        Repetir contraseña: 
        <input type="password" name="confirmarPassword" id="confirmarPassword" required placeholder="Repita la contraseña" required><br>
        <label for="tipoUsuario">Tipo de usuario actual:</label>
        <input type="text" name="tipoUsuario" id="tipoUsuario" value = "<?php echo $tipo;?>" readonly><br>
        <label for="tipo">Nuevo tipo de usuario:</label>
        <select name="tipo" id="tipo">
            <option value="admin">administrador</option>
            <option value="cliente">cliente</option>
            <option value="ventas">ventas</option>
        </select>
        <br>
        <br>
        <button type="submit">Editar</button>
        <a href="index.php">Cancelar</a>
    </form>


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