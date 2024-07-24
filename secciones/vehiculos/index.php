<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
//si no existe la variable de sesión usuario_id, se redirige al index
if($_SESSION["usuario_tipo"]=="cliente" || $_SESSION["usuario_tipo"]=="ventas"){
    header('Location:../../index.php');
    exit();
}


//******Inicia código para eliminar registro******
//Para recolectar información del url con el botón "eliminar" método GET
//Se verifica que el id exista en el url
if(isset($_GET['txtID'])){
 //Se crea variable para asignar el valor del id seleccionado
  //Si esta variable existe, se asigna ese valor, de lo contrario se queda
  $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
  //Se prepara sentencia para borrar dato seleccionado (id)
  $sentencia = $conexion->prepare("DELETE FROM vehiculo WHERE id=:id");
  //Asignar los valores que vienen del método GET (id seleccionado por params)
  //Se asigna el valor de la variable a la sentencia
  $sentencia->bindParam(":id",$txtID);
  //Se ejecuta la sentencia con el valor asignado para borrar
  $sentencia->execute();
  //Mensaje de confirmación de borrado que activa Sweet Alert 2
  //Llama a código de templates/footer.php
  $mensaje="Vehículo eliminado";
  //Redirecionar después de eliminar a la lista de puestos
  header("Location:index.php?mensaje=".$mensaje);
}
//******Termina código para eliminar registro******

//******Inicia código para mostrar todos los registros******
//Se prepara sentencia para seleccionar todos los datos 
$sentencia = $conexion->prepare("SELECT * FROM vehiculo");
$sentencia->execute();
$tipo = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($cupones);
//******Termina código para mostrar todos los registros******
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<h2>Vehículos</h2>

<!--Nuevo look inicia-->
<div class="card">
  <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Añadir vehículos</a>
  </div>
  <div class="card-body">
    <div class="table-responsive-lg">
      <table class="table" id="tabla_id">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Modelo</th>
            <th scope="col">Año</th>
            <th scope="col">Placas</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($tipo as $registro){ ?>
          <tr class="">
            <td scope="row"><?php echo $registro['id']?></td>
            <td><?php echo $registro['modelo']?></td>
            <td><?php echo $registro['anio']?></td>
            <td><?php echo $registro['placas']?></td>
            <td>
              <div class="text-center" class="dropdown">
                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <!--Descomentar cuando exista esta funcionalidad-->
                  <!--<li><a class="dropdown-item" href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a></li>-->
                  <!--Se sustituye el link del registro por la función SweatAlert para confirmar borrado-->
                  <li><a class="dropdown-item" href="javascript:borrar(<?php echo $registro['id']?>);">Eliminar</a></li>
                </ul>
              </div>
            </td>
          </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
</div>
  <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>