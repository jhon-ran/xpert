<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
if($_POST){
        print_r($_POST);
    
        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        $nombre = (isset($_POST["nombre"])? $_POST["nombre"]:"");
        $descuento = (isset($_POST["descuento"])? $_POST["descuento"]:"");
        $inicioValidez = (isset($_POST["inicioValidez"])? $_POST["inicioValidez"]:"");
        $terminoValidez = (isset($_POST["terminoValidez"])? $_POST["terminoValidez"]:"");
        $restricciones = (isset($_POST["restricciones"])? $_POST["restricciones"]:"");
        //Preparar la inseción de los datos enviados por POST
        $sentencia = $conexion->prepare("INSERT INTO cupones(id,nombre,descuento,inicioValidez,terminoValidez,restricciones) 
        VALUES (null, :nombre, :descuento, :inicioValidez, :terminoValidez, :restricciones)" );
        //Asignar los valores que vienen del formulario (POST)
        $sentencia->bindParam(":nombre",$nombre);
        $sentencia->bindParam(":descuento",$descuento);
        $sentencia->bindParam(":inicioValidez",$inicioValidez);
        $sentencia->bindParam(":terminoValidez",$terminoValidez);
        $sentencia->bindParam(":restricciones",$restricciones);
        //Se ejecuta la sentencia con los valores de param asignados
        $sentencia->execute();
        //Mensaje de confirmación de creado que activa Sweet Alert 2
        $mensaje="Registro creado";
        //Redirecionar después de crear a la lista de cupones
        header("Location:index.php?mensaje=".$mensaje);
    }
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<!--
<form action="crear.php" id="crearCupones" method="post">
        Nombre:
        <input type="text" name="nombre" id="nombre"><br>
        Descuento:
        <input type="number" name="descuento" id="descuento"><br>
        Inicio de validez:
        <input type="datetime-local" name="inicioValidez" id="inicioValidez"><br>
        Termino de validez:
        <input type="datetime-local" name="terminoValidez" id="terminoValidez"><br>
        <p>Restricciones:</p>
        <textarea name="restricciones" id="restricciones" cols="40" rows="5"></textarea><br>
        <button type="submit">Crear cupón</button>
        <a href="index.php">Cancelar</a>
</form>
-->
<h2>Crear Cupones</h2>
<!--Nuevo look empieza-->
<div class="card">
        <div class="card-header">Datos del cupón</div>
                <div class="card-body">
                        <form action="crear.php" id="crearCupones" method="post">
                                <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="descuento" class="form-label">Descuento</label>
                                        <input type="number" class="form-control" name="descuento" id="descuento" aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="inicioValidez" class="form-label">Inicio de validez</label>
                                        <input type="datetime-local" class="form-control" name="inicioValidez" id="inicioValidez"aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="terminoValidez" class="form-label">Termino de validez</label>
                                        <input type="datetime-local" class="form-control" name="terminoValidez" id="terminoValidez"aria-describedby="helpId" placeholder=""/>
                                </div>
                                <div class="mb-3">
                                        <label for="restricciones">Restricciones</label>
                                        <textarea class="form-control" name="restricciones" id="restricciones" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Registrar</button>
                                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                        </form>
                </div>
        <div class="card-footer text-muted"></div>
</div>
    <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>