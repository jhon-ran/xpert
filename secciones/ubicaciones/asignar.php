<?php
//Importar conexión a BD
include("../../bd.php");
//se inicializa variable de sesión
session_start();
//se guarda el valor de la variable de usuario logeado para usarla como modificador en tabla tours_historial
$usuario_id = $_SESSION['usuario_id'];

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
    $txtID = $_GET['txtID'];
    // Prepara la sentencia para obtener la ubicación
    $sentencia = $conexion->prepare("SELECT * FROM ubicaciones WHERE id = :id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    // Se asigna el ID de la ubicación al campo ubicacion en la tabla tours
    $ubicacion = $registro["id"];
}
//******Termina código para recibir registro******


//Verifciar si hay datos por el metodo post
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include("../../conexion.php");
    $errores = array();
    $succes = false;
    // Se obteneiene el id del tour seleccionado
    $txtIDTour = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : "";
    // ID de la ubicación desde el formulario
    $txtID = isset($_POST['txtID']) ? $_POST['txtID'] : "";
    //validación de campo
    if (empty($txtIDTour)){
        $errores['ubicacion'] = "Debe seleccionar un tour";
    }

    foreach($errores as $error){
        echo $error;
    }

    if(empty($errores)){

            /*INICIA CODIGO PARA VINCULAR EL USUARIO LOGGEADO PARA QUE
            APAREZCA COMO MODIFICADOR CUANDO EN LA TABLA tours_registro*/
            try {
                    // Conexión a la base de datos
                    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Preparar la sentencia para establecer la variable de sesión en MySQL
                    $stmt = $conexion->prepare("SET @modificador = :usuario_id");

                    // Vincular el parámetro :usuario_id a la variable $usuario_id
                    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

                    // Ejecutar la sentencia
                    $stmt->execute();

                    echo "Variable de sesión @modificador establecida correctamente.";
            } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
            }
            /*TERMINA CODIGO PARA VINCULAR EL USUARIO LOGGEADO PARA QUE
            APAREZCA COMO MODIFICADOR CUANDO EN LA TABLA tours_registro*/
            
        try{
            // Se vincula el ID de la ubicación y el ID del tour seleccionado
            $sentencia = $conexion->prepare("UPDATE tours SET ubicacion = :ubicacion WHERE id = :id");
            $sentencia->bindParam(":ubicacion", $txtID);
            $sentencia->bindParam(":id", $txtIDTour);

            $sentencia->execute();
            $mensaje = "Ubicación asignada a tour";

            header("Location:index.php?mensaje=".$mensaje);
        } catch(Exception $ex){
            echo "Error de conexión: " . $ex->getMessage();
        }
    } else {
        $succes = false;
    }
}

// Consulta para obtener todos los tours
$sentencia = $conexion->prepare("SELECT * FROM tours");
$sentencia->execute();
$tours = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    <!--Nuevo look inicia-->
    <header class="text-center my-4">
            <h1>Asignar dirección a tour</h1>
    </header>
    
<div class="row my-2">
    <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Asignación</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="asignar.php" id="asignarUbicacion" method="post">
                <div class="mb-3">
                    <label for="id" class="form-label">ID de dirección</label>
                    <input type="text" class="form-control" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="ubicacion" class="form-label">Tour</label>
                        <select class="form-select form-select-sm" name="ubicacion" id="ubicacion" onclick="validateAsignarUbicacion()" required>
                            <option value="" selected>Seleccione una opción</option>
                            <?php foreach($tours as $tour){ ?>
                                <option <?php echo ($ubicacion == $tour['id']) ? "selected" : "";?> value="<?php echo $tour['id'];?>"><?php echo $tour['titulo'];?></option>
                            <?php }?>
                        </select>
                    <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                    <span id="errorAsignar" class="error"></span>   
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['ubicacion'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['ubicacion']; ?></div>
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
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>
<!-- Llama funcion para validar asignar ubicación a tours-->
<script src="../../js/validarAsignarUbicacion.js"> </script>