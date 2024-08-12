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

  //******Inicia código para eliminar foto de carpeta ******
  //buscar el archivo relacionado con la foto
  $sentencia = $conexion->prepare("SELECT foto FROM logos WHERE id=:id");
  $sentencia->bindParam(":id",$txtID);
  $sentencia->execute();
  //Mensaje de confirmación de borrado que activa Sweet Alert 2
  //Llama a código de templates/footer.php
  $mensaje="Logo eliminado";
  //se recupera solo un registro asosiado al id
  $foto_logo = $sentencia->fetch(PDO::FETCH_LAZY);

  //Si existe ese archivo de la foto o diferente de vacío
  if(isset($foto_logo["foto"]) && $foto_logo["foto"]!=""){
      //Si el archivo existe dentro de la carpeta actual
      if(file_exists("./".$foto_logo["foto"])){
          //Se elimina el archivo
          unlink("./".$foto_logo["foto"]);
      }
  }
  //******Termina código para eliminar foto de carpeta logos******

    //Se prepara sentencia para borrar dato seleccionado (id)
    $sentencia = $conexion->prepare("DELETE FROM logos WHERE id=:id");
    //Asignar los valores que vienen del método GET (id seleccionado por params)
    //Se asigna el valor de la variable a la sentencia
    $sentencia->bindParam(":id",$txtID);
    //Se ejecuta la sentencia con el valor asignado para borrar
    $sentencia->execute();
    //Redirecionar después de eliminar a la lista de puestos
    header("Location:index.php");

}
//******Termina código para eliminar registro******

//******Inicia código para mostrar todos los registros******
//Se prepara sentencia para seleccionar todos los datos 
$sentencia = $conexion->prepare("SELECT * FROM logos");
$sentencia->execute();
$lista_logos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($cupones);
//******Termina código para mostrar todos los registros******
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<h2>Logos de redes</h2>

<!--Nuevo look inicia-->
<div class="card my-2">
  <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Añadir logo</a>
  </div>
  <div class="card-body">
    <div class="table-responsive-lg">
      <table class="table" id="tabla_id">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Red social</th>
            <th scope="col">Logo</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($lista_logos as $logo){ ?>
          <tr class="">
            <td scope="row"><?php echo $logo['id']?></td>
            <td><?php echo $logo['nombre']?></td>
            <td class="w-25 p-3">
              <img src="<?php echo $logo['foto']?>" class="img-thumbnail" alt="logotipo">
            </td>
            <td>
              <div class="text-center" class="dropdown">
                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  <li><a class="dropdown-item" href="editar.php?txtID=<?php echo $logo['id']?>">Editar</a></li>
                  <!--Se sustituye el link del registro por la función SweatAlert para confirmar borrado-->
                  <li><a class="dropdown-item" href="javascript:borrar(<?php echo $logo['id']?>);">Eliminar</a></li>
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