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
        //Redirecionar a la lista de puestos
        header("Location:index.php");
    }
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Crear Cupones</h1>
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


<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>