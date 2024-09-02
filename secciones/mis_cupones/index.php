<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
//si no existe la variable de sesión usuario_id, se redirige al index
if($_SESSION["usuario_tipo"]=="cliente") {
    header('Location:../../index.php');
    exit();
}

$txtID = $_SESSION['usuario_id'];

//******Inicia código para mostrar todos los registros******
//Se prepara sentencia para seleccionar los cupones asociados al usuario loggeado. Explicación:
/*
Se seleccionan los campos de la tabla cupones (c.id, c.nombre, c.descuento, c.inicioValidez, c.terminoValidez, c.restricciones).
Se realiza un INNER JOIN entre las tablas cupones (c) y usuarios_cupones (uc) usando la relación id_cupon.
Se filtra la consulta con WHERE para que solo se incluyan los registros donde uc.id_usuario coincida con la variable de sesión $_SESSION['usuario_id']
*/
$sentencia = $conexion->prepare("SELECT c.id, c.nombre, c.descuento, c.inicioValidez, c.terminoValidez, c.restricciones
FROM cupones c
INNER JOIN usuarios_cupones uc ON c.id = uc.id_cupon
WHERE uc.id_usuario = :id;
");

$sentencia->bindParam(":id",$txtID);
$sentencia->execute();
$cupones = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($cupones);
//******Termina código para mostrar todos los registros******
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<br>
<div class="p-4 mb-3 bg-light rounded-3 shadow-sm">
    <div class="container-fluid text-center">
        <h1 class="display-5 fw-bold">Tus cupones asignados</h1>
    </div>
</div>

<!--Nuevo look inicia-->
<div class="card my-4">
  <div class="card-header">
  </div>
  <div class="card-body">
    <div class="table-responsive-lg">
      <table class="table" id="tabla_id">
        <thead>
          <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Descuento</th>
            <th scope="col">Inicio de validez</th>
            <th scope="col">Termino de validez</th>
            <th scope="col">Restricciones</th>

            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($cupones as $registro){ ?>
          <tr class="">
            <td scope="row"><?php echo $registro['nombre']?></td>
            <td><?php echo $registro['descuento']?></td>
            <td><?php echo $registro['inicioValidez']?></td>
            <td><?php echo $registro['terminoValidez']?></td>
            <td><?php echo $registro['restricciones']?></td>

            <td>
            </td>
          </tr>
          <?php }?>
        </tbody>
      </table>
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

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>


