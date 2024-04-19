<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 

//******Inicia código para mostrar todos los registros******
//Se prepara sentencia para seleccionar todos los datos 
$sentencia = $conexion->prepare("SELECT * FROM cupones");
$sentencia->execute();
$cupones = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($cupones);
//******Termina código para mostrar todos los registros******
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Bienvenidos al index de cupones</h1>

<a href="crear.php">Crear cupones</a>
<br>
  <br>
<table>
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Descuento</th>
    <th>Inicio de validez</th>
    <th>Termino de validez</th>
    <th>Restricciones</th>
    <th>Fecha de creación</th>
    <th>Acciones</th>
  </tr>
  <?php foreach($cupones as $registro){ ?>
  <tr>
    <td><?php echo $registro['id']?></td>
    <td><?php echo $registro['nombre']?></td>
    <td><?php echo $registro['descuento']?></td>
    <td><?php echo $registro['inicioValidez']?></td>
    <td><?php echo $registro['terminoValidez']?></td>
    <td><?php echo $registro['restricciones']?></td>
    <td><?php echo $registro['fechaCreacion']?></td>
    <td><a href="">Editar</a> | <a href="">Eliminar</a>  </td>
  </tr>
  <?php }?>
</table>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>