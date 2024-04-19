<?php 
include("../../bd.php"); 

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
        $sentencia->bindParam(":restricciones",$restricciones);
        //Se ejecuta la sentencia con los valores de param asignados
        $sentencia->execute();
        header("Location:index.php");
    }
    //******Termina código para recibir registro******
?>


<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Editar Cupones</h1>
<br>
<form action="editar.php" id="editarCupones" method="post">
        ID:
        <input type="text" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" placeholder="ID"/><br>
        Nombre:
        <input type="text" name="nombre" id="nombre" value = "<?php echo $nombre;?>"><br>
        Descuento:
        <input type="number" name="descuento" id="descuento" value = "<?php echo $descuento;?>"><br>
        Inicio de validez:
        <input type="datetime-local" name="inicioValidez" id="inicioValidez" value = "<?php echo $inicioValidez;?>"><br>
        Termino de validez:
        <input type="datetime-local" name="terminoValidez" id="terminoValidez" value = "<?php echo $terminoValidez;?>"><br>
        <p>Restricciones:</p>
        <!-- Para que se muestren los datos en textarea se deben poner en placeholder-->
        <textarea name="restricciones" id="restricciones" cols="40" rows="5" value = "<?php echo $restricciones;?>" placeholder="<?php echo $restricciones;?>"></textarea><br>
        <button type="submit">Editar cupón</button>
        <a href="index.php">Cancelar</a>
</form>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>