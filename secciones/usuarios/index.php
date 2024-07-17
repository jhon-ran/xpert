<!-- Importar conexión a BD-->
<?php include("../../bd.php");
//se inicializa variable de sesión
session_start();

//******Inicia código para eliminar registro******
//Para recolectar información del url con el botón "eliminar" método GET
//Se verifica que el id exista en el url
if(isset($_GET['txtID'])){
  //Se crea variable para asignar el valor del id seleccionado
   //Si esta variable existe, se asigna ese valor, de lo contrario se queda
   $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
   //Se prepara sentencia para borrar dato seleccionado (id)
   $sentencia = $conexion->prepare("DELETE FROM usuarios WHERE id=:id");
   //Asignar los valores que vienen del método GET (id seleccionado por params)
   //Se asigna el valor de la variable a la sentencia
   $sentencia->bindParam(":id",$txtID);
   //Se ejecuta la sentencia con el valor asignado para borrar
   $sentencia->execute();
  //Mensaje de confirmación de borrado que activa Sweet Alert 2
  $mensaje="Registro eliminado";
  //Redirecionar después de eliminar a la lista de puestos
  //Llama a código de templates/footer.php
  header("Location:index.php?mensaje=".$mensaje);
 }
 //******Termina código para eliminar registro******

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



<h2>Usuarios registrados</h2>
<!--Nuevo look inicia-->
<div class="card">
  <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" role="button" >Registrar</a>
  </div>
  <div class="card-body">
    <div
      class="table-responsive-md"
    >
      <table class="table" id="tabla_id">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Email</th>
            <th scope="col">Tipo</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($usuarios as $registro){ ?>
          <tr class="">
            <td scope="row"><?php echo $registro['id']?></td>
            <td><?php echo $registro['nombre']?></td>
            <td><?php echo $registro['apellidos']?></td>
            <td><?php echo $registro['email']?></td>
            <td><?php echo $registro['tipo']?></td>
            <td>
              <!--
              <a name="" id="" class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']?>" role="button">Editar</a>
              <a name="" id="" class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']?>);">Eliminar</a>
              -->
              <div class="text-center" class="dropdown">
                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  <li><a class="dropdown-item" href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a></li>
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