<?php 
include("../../bd.php"); 
//se inicializa variable de sesión
session_start();

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

//******Inicia código para modificar registro******
if($_POST){
        //Descomentar esta línea para comprobar que se reciben datos
        //print_r($_POST);
    
        $txtID = (isset($_POST['txtID']))?$_POST['txtID']:"";
        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        $nombre = (isset($_POST["nombre"])? $_POST["nombre"]:"");
        $descuento = (isset($_POST["descuento"])? $_POST["descuento"]:"");
        $inicioValidez = (isset($_POST["inicioValidez"])? $_POST["inicioValidez"]:"");
        $terminoValidez = (isset($_POST["terminoValidez"])? $_POST["terminoValidez"]:"");
        $restricciones = (isset($_POST["restricciones"])? $_POST["restricciones"]:"");
        //Preparar modificar el registro enviados por POST
        $sentencia = $conexion->prepare("UPDATE cupones SET nombre=:nombre, 
        descuento=:descuento, inicioValidez=:inicioValidez, terminoValidez=:terminoValidez, 
        restricciones=:restricciones WHERE id=:id");
        //Asignar los valores que vienen del formulario (POST)
        $sentencia->bindParam(":id",$txtID);
        $sentencia->bindParam(":nombre",$nombre);
        $sentencia->bindParam(":descuento",$descuento);
        $sentencia->bindParam(":inicioValidez",$inicioValidez);
        $sentencia->bindParam(":terminoValidez",$terminoValidez);
        if($restricciones == null){
                //Si el campo de restricciones está vacío se le asigna un valor por defecto
                $restricciones = "Ninguna";
                $sentencia->bindParam(":restricciones",$restricciones);
        }
        $sentencia->bindParam(":restricciones",$restricciones);
        //Se ejecuta la sentencia con los valores de param asignados
        $sentencia->execute();
        //Mensaje de confirmación de modificación que activa Sweet Alert 2
        $mensaje="Registro modificado";
        //Redirecionar después de modificar a la lista de cupones
        header("Location:index.php?mensaje=".$mensaje);
    }
    //******Termina código para recibir registro******
?>


<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<h2>Editar Cupones</h2>
<!--Nuevo look empieza-->
<div class="card">
        <div class="card-header">Datos del cupón</div>
                <div class="card-body">
                        <form action="editar.php" id="editarCupones" method="post">
                                <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" value = "<?php echo $nombre;?>" name="nombre" id="nombre" aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="descuento" class="form-label">Descuento</label>
                                        <input type="number" class="form-control" value = "<?php echo $descuento;?>" name="descuento" id="descuento" aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="inicioValidez" class="form-label">Inicio de validez</label>
                                        <input type="datetime-local" class="form-control" name="inicioValidez" id="inicioValidez" value = "<?php echo $inicioValidez;?>" aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="terminoValidez" class="form-label">Termino de validez</label>
                                        <input type="datetime-local" class="form-control" name="terminoValidez" id="terminoValidez" value = "<?php echo $terminoValidez;?>" aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="restricciones">Restricciones</label>
                                        <textarea class="form-control" name="restricciones" id="restricciones" rows="3"><?php echo $restricciones;?></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Editar</button>
                                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                        </form>
                </div>
        <div class="card-footer text-muted"></div>
</div>
    <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>