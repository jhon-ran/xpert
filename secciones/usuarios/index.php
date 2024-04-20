<!-- Importar conexión a BD-->
<?php include("../../bd.php");

//******Inicia código para mostrar todos los registros******
//Se prepara sentencia para seleccionar todos los datos 
$sentencia = $conexion->prepare("SELECT * FROM usuarios");
$sentencia->execute();
$usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($cupones);
//******Termina código para mostrar todos los registros******

?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<br>
<h1>Bienvenidos al index de usuarios</h1>
<a href="crear.php">Registrar</a>
 <br>
  <br>
<table>
  <tr>
  <th>ID</th>
    <th>Nombre</th>
    <th>Apellidos</th>
    <th>Email</th>
    <th>Tipo de usuario</th>
    <th>Acciones</th>
  </tr>
  <?php foreach($usuarios as $registro){ ?>
  <tr>
    <td><?php echo $registro['id']?></td>
    <td><?php echo $registro['nombre']?></td>
    <td><?php echo $registro['apellidos']?></td>
    <td><?php echo $registro['email']?></td>
    <td><?php echo $registro['tipo']?></td>
    <!--Envia el id através de la url-->
    <td><a href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a> | 
    <a href="index.php?txtID=<?php echo $registro['id']?>">Eliminar</a>  </td>
  </tr>
  <?php }?>
</table>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>