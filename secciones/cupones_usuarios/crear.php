<?php
//Importar conexión a BD
include("../../bd.php");
//se inicializa variable de sesión
session_start();
//si el usuario es ventas o cliente se redirige al login
if($_SESSION["usuario_tipo"]=="cliente" || $_SESSION["usuario_tipo"]=="ventas"){
    header('Location:../../index.php');
    exit();
}
//Verifciar si hay datos por el metodo post
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include("../../conexion.php");
    //Array para guardar los errores
    $errores= array();
    //Variable para saber si se ha registrado correctamente
    $succes = false;
    //Imprimir los datos del formulario en pantalla
    

    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $id_usuario =(isset($_POST["id_usuario"]))? $_POST["id_usuario"]:null;
    $id_cupon =(isset($_POST["id_cupon"]))? $_POST["id_cupon"]:null;

    //Si los datos están, se llena el array con los mensajes de errores
    if (empty($id_usuario)){
         $errores['id_usuario']= "Debe seleccionar un vendedor";
    }
  
    if (empty($id_cupon)){
         $errores['id_cupon']= "Debe seleccionar un cupón";
    }


        //******Inicia validación si id_tipo_staff existente en bd**
        /*
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
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
        */
        //******Termina validación de email existente en bd*****

    //Imprimir errores en pantalla si los hay
   foreach($errores as $error){
        $error;
   }

   //Si no hay errores (array de errores vacio)
   if(empty($errores)){
        //Conexion a la base de datos
        try{
            //Preparar la inseción de los datos enviados por POST
            $sentencia = $conexion->prepare("INSERT INTO usuarios_cupones(id,id_usuario,id_cupon) 
            VALUES (null, :id_usuario, :id_cupon)");
            
            //Asignar los valores que vienen del formulario (POST)
            $sentencia->bindParam(":id_usuario",$id_usuario);
            $sentencia->bindParam(":id_cupon",$id_cupon);

            //Se ejecuta la sentencia con los valores de param asignados
            $sentencia->execute();
    
            //Mensaje de confirmación de creación que activa Sweet Alert 2
            //Llama a código de templates/header.php
            $mensaje="Cupón asignado a vendedor";
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

//query para obtener los usuarios que son de tipo ventas
$sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE tipo='ventas'");
$sentencia->execute();
//se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
$vendedores = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//query para obtener los cupones
$sentencia = $conexion->prepare("SELECT * FROM cupones");
$sentencia->execute();
//se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
$lista_cupones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    <!--Nuevo look inicia-->
    <header class="text-center">
            <h1>Asignar cupón</h1>
    </header>
    
<div class="row my-2">
    <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Asignación</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="crear.php" id="asignarCupon" method="post">
                <div class="mb-3">
                    <label for="id_usuario" class="form-label">Vendedor</label>
                    <select class="form-select form-select-sm" name="id_usuario" id="id_usuario" onclick="validateAsignarCupon()" required>
                        <option value="" selected>Seleccione un vendedor</option>
                            <?php foreach($vendedores as $vendedor){ ?>
                                    <option value="<?php echo $vendedor['id']?>"><?php echo $vendedor["nombre"], ' ', $vendedor["apellidos"]?></option>
                            <?php }?>
                    </select>
                    <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                    <span id="errorUsuario" class="error"></span>   
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['id_usuario'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['id_usuario']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="id_cupon" class="form-label">Cupón</label>
                    <select class="form-select form-select-sm" name="id_cupon" id="id_cupon" onclick="validateAsignarCupon()" required>
                        <option value="" selected>Seleccione un cupón</option>
                            <?php foreach($lista_cupones as $cupon){ ?>
                                    <option value="<?php echo $cupon['id']?>"><?php echo $cupon["nombre"]?></option>
                            <?php }?>
                    </select>
                    <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                    <span id="errorCupon" class="error"></span>   
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['id_cupon'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['id_cupon']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <button type="submit" id="submitBtn" class="btn btn-success">Asignar</button>
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

<!-- Llama funcion para validar usuarios con cupones-->
<script src="../../js/validarAsignarCupones.js"> </script>