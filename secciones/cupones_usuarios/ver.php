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

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
        //Si esta variable existe, se asigna ese valor, de lo contrario se queda
        $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
        //Se prepara sentencia para editar dato seleccionado (id)
        $sentencia = $conexion->prepare("SELECT * FROM usuarios_cupones WHERE id=:id");
        //Asignar los valores que vienen del método GET (id seleccionado por params)
        $sentencia->bindParam(":id",$txtID);
        //Se ejecuta la sentencia con el valor asignado para borrar
        $sentencia->execute();
        //Popular el formulario con los valores de 1 registro
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);
        //Asignar los valores que vienen del formulario (POST)
        $id_usuario = $registro["id_usuario"];
        $id_cupon = $registro["id_cupon"];
}
//******Termina código para recibir registro******

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
<!--Nuevo look inicia-->
<header class="text-center my-3">
    <h1>Cupón asignado</h1>
</header>

<div class="row my-2">
    <div class="col-md-4"><br><br></div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Datos de asignación</div>
            <div class="card-body">
                <!-- ID -->
                <div class="mb-3">
                    <label for="txtID" class="form-label fw-bold">ID:</label>
                    <div class="text-muted" id="txtID"><?php echo $txtID; ?></div>
                </div>
                <hr>

                <!-- Vendedor-->
                <div class="mb-3">
                    <label for="id_usuario" class="form-label fw-bold">Vendedor:</label>
                    <div class="text-muted" id="id_usuario">
                        <?php 
                            foreach($vendedores as $vendedor) {
                                if ($id_usuario == $vendedor['id']) {
                                    echo $vendedor['nombre'] . ' ' . $vendedor["apellidos"];
                                    break;
                                }
                            }
                        ?>
                    </div>
                </div>
                <hr>

                <!-- Cupón -->
                <div class="mb-3">
                    <label for="id_cupon" class="form-label fw-bold">Cupón:</label>
                    <div class="text-muted" id="id_cupon">
                        <?php 
                            foreach($lista_cupones as $cupon) {
                                if ($id_cupon == $cupon['id']) {
                                    echo $cupon['nombre'];
                                    break;
                                }
                            }
                        ?>
                    </div>
                </div>
                <hr>
                <!-- Fecha asignación -->
                <div class="mb-3">
                    <label for="id_cupon" class="form-label fw-bold">Fecha asignación:</label>
                    <div class="text-muted" id="id_cupon">
                    <?php echo date("d/m/Y H:i", strtotime($registro['fecha_asignacion']))?>
                    </div>
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