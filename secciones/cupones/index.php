<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 

//******Inicia código para eliminar registro******
//Para recolectar información del url con el botón "eliminar" método GET
//Se verifica que el id exista en el url
if(isset($_GET['txtID'])){
 //Se crea variable para asignar el valor del id seleccionado
  //Si esta variable existe, se asigna ese valor, de lo contrario se queda
  $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
  //Se prepara sentencia para borrar dato seleccionado (id)
  $sentencia = $conexion->prepare("DELETE FROM cupones WHERE id=:id");
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
<h2>Cupones existentes</h2>

<!--Nuevo look inicia-->
<div class="card">
  <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" role="button" >Crear cupones</a>
  </div>
  <div class="card-body">
    <div class="table-responsive-sm">
      <table class="table" id="tabla_id">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Descuento</th>
            <th scope="col">Inicio de validez</th>
            <th scope="col">Termino de validez</th>
            <th scope="col">Restricciones</th>
            <th scope="col">Fecha de creación</th>
            <th scope="col">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($cupones as $registro){ ?>
          <tr class="">
            <td scope="row"><?php echo $registro['id']?></td>
            <td><?php echo $registro['nombre']?></td>
            <td><?php echo $registro['descuento']?></td>
            <td><?php echo $registro['inicioValidez']?></td>
            <td><?php echo $registro['terminoValidez']?></td>
            <td><?php echo $registro['restricciones']?></td>
            <td><?php echo $registro['fechaCreacion']?></td>
            <td>
              <a name="" id="" class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']?>" role="button">Editar</a>
              <!--Se sustituye el link del registro por la función SweatAlert para confirmar borrado-->
              <a name="" id="" class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']?>);">Eliminar</a>
            </td>
          </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
</div>
  <!--Nuevo look termina-->

<script>
  function borrar(id){
    //Se llama a la función del SweetAlert API para mostrar un mensaje de confirmación
    Swal.fire({
      title: "¿Estás seguro que quieres borrar el registro?",
      showCancelButton: true,
      confirmButtonText: "Sí"
    }).then((result) => {
        //Si el usuario confirma la eliminación, se redirecciona a la página de eliminación
        if (result.isConfirmed) {
          window.location="index.php?txtID="+id;
        } 
    });
        //index.php?txtID=
  }
</script>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>