<?php 
include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
//se guarda el valor de la variable de usuario logeado para usarla como modificador en tabla cupones_historial
$usuario_id = $_SESSION['usuario_id'];

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
        //Si esta variable existe, se asigna ese valor, de lo contrario se queda
        $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
        //Se prepara sentencia para editar dato seleccionado (id)
        $sentencia = $conexion->prepare("SELECT * FROM cupones WHERE id=:id");
        //Asignar los valores que vienen del método GET (id seleccionado por params)
        $sentencia->bindParam(":id",$txtID);
        //Se ejecuta la sentencia con el valor asignado para borrar
        $sentencia->execute();
        //Popular el formulario con los valores de 1 registro
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);
        //Asignar los valores que vienen del formulario (POST)
        $nombre = $registro["nombre"];
        $descuento = $registro["descuento"];
        $inicioValidez = $registro["inicioValidez"];
        $terminoValidez = $registro["terminoValidez"];
        $restricciones = $registro["restricciones"];
        //print_r($restricciones);
}
//******Termina código para recibir registro******

?>


<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>
<script src="../../js/validarFechas.js"> </script>

<!--Nuevo look empieza-->
<header class="text-center">
    <h1>Cupón</h1>
</header>

<div class="row my-2">
    <div class="col-md-4"><br><br></div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Datos del cupón</div>
            <div class="card-body">
                <!-- ID Section -->
                <div class="mb-3">
                    <label for="txtID" class="form-label fw-bold">ID:</label>
                    <div class="text-muted" id="txtID"><?php echo $txtID; ?></div>
                </div>
                <hr>

                <!-- Nombre Section -->
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre:</label>
                    <div class="text-muted" id="nombre"><?php echo $nombre; ?></div>
                </div>
                <hr>

                <!-- Descuento Section -->
                <div class="mb-3">
                    <label for="descuento" class="form-label fw-bold">Descuento:</label>
                    <div class="text-muted" id="descuento"><?php echo $descuento; ?></div>
                </div>
                <hr>

                <!-- Inicio de validez Section -->
                <div class="mb-3">
                    <label for="inicioValidez" class="form-label fw-bold">Inicio de validez:</label>
                    <div class="text-muted" id="inicioValidez"><?php echo date("d/m/Y H:i", strtotime($inicioValidez)); ?></div>
                </div>
                <hr>

                <!-- Término de validez Section -->
                <div class="mb-3">
                    <label for="terminoValidez" class="form-label fw-bold">Término de validez:</label>
                    <div class="text-muted" id="terminoValidez"><?php echo date("d/m/Y H:i", strtotime($terminoValidez)); ?></div>
                </div>
                <hr>

                <!-- Restricciones Section -->
                <div class="mb-3">
                    <label for="restricciones" class="form-label fw-bold">Restricciones:</label>
                    <div class="text-muted" id="restricciones"><?php echo nl2br($restricciones); ?></div>
                </div>
                <hr>

                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Regresar</a>
            </div>
        </div>
    </div>
</div>
<!--Nuevo look termina-->


<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>