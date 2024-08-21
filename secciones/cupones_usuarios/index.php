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
   $sentencia = $conexion->prepare("DELETE FROM usuarios_cupones WHERE id=:id");
   //Asignar los valores que vienen del método GET (id seleccionado por params)
   //Se asigna el valor de la variable a la sentencia
   $sentencia->bindParam(":id",$txtID);
   //Se ejecuta la sentencia con el valor asignado para borrar
   $sentencia->execute();
  //Mensaje de confirmación de borrado que activa Sweet Alert 2
  $mensaje="Asignación de cupón eliminada";
  //Redirecionar después de eliminar a la lista de puestos
  //Llama a código de templates/footer.php
  header("Location:index.php?mensaje=".$mensaje);
 }
 //******Termina código para eliminar registro******

//******Inicia código para mostrar todos los registros******
//Se prepara sentencia para seleccionar todos los datos 

//******Inicia código para mostrar todos los registros******
/* Query join para devolverá una lista de todas las asociaciones entre tours y logos. Explicación: 
redes_tour.id AS association_id: El identificador único de la asociación en la tabla redes_tour.
tours.id AS tour_id: El identificador único del tour.
tours.titulo AS tour_title: El título del tour.
logos.nombre AS logo_name: El nombre del logo.
logos.id AS logo_id: El identificador único del logo.
FROM redes_tour - Comienza la consulta desde la tabla redes_tour, que contiene las relaciones entre tours y logos.
LEFT JOIN tours ON tours.id = redes_tour.id_tour: Realiza una unión a la izquierda entre redes_tour y tours para incluir todos los registros de redes_tour y los registros coincidentes de tours. Si no hay coincidencia, los campos de tours serán NULL.
LEFT JOIN logos ON logos.id = redes_tour.id_logo: Realiza una unión a la izquierda entre redes_tour y logos para incluir todos los registros de redes_tour y los registros coincidentes de logos. Si no hay coincidencia, los campos de logos serán NULL.
*/
$sentencia = $conexion->prepare("SELECT 
    usuarios_cupones.id AS association_id,
    usuarios.id AS user_id,
    usuarios.nombre AS user_name,
    cupones.nombre AS cupon_name,
    cupones.id AS cupon_id
FROM 
    usuarios_cupones
LEFT JOIN 
    usuarios ON usuarios.id = usuarios_cupones.id_usuario
LEFT JOIN 
    cupones ON cupones.id = usuarios_cupones.id_cupon;");

$sentencia->execute();
$asignaciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($cupones);
//******Termina código para mostrar todos los registros******

?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<br>
<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Asignación de cupones a vendedores</h1>
    </div>
</div>


<h2>Copones asignados</h2>
<!--Nuevo look inicia-->


<div class="card my-2">
  <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Asignar cupón a vendedor</a>
  </div>
  <div class="card-body">
    <div
      class="table-responsive-md"
    >
      <table class="table" id="tabla_id">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Vendedor</th>
            <th scope="col">Cupón</th>
            <th scope="col">ID asignación</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($asignaciones as $asignacion){ ?>
          <tr class="">
            <td scope="row"><?php echo $asignacion['user_id']?></td>
            <td><?php echo $asignacion['user_name']?></td>
            <td><?php echo $asignacion['cupon_name']?></td>
            <td><?php echo $asignacion['association_id']?></td>
            <td>
              <div class="text-center" class="dropdown">
                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <!--Descomentr cuando qrchivo este creado-->
                  <li><a class="dropdown-item" href="editar.php?txtID=<?php echo $asignacion['association_id']?>">Editar</a></li>
                  <!--Se sustituye el link del registro por la función SweatAlert para confirmar borrado-->
                  <li><a class="dropdown-item" href="javascript:borrar(<?php echo $asignacion['association_id']?>);">Eliminar</a></li>
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