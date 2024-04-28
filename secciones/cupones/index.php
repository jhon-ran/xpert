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

    
<h1>Cupones existentes</h1>

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
    <!--Envia el id através de la url-->
    <td><a href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a> | 
    <a href="index.php?txtID=<?php echo $registro['id']?>">Eliminar</a>  </td>
  </tr>
  <?php }?>
</table>

<!--Nuevo look inicia-->
<div class="card">
  <div class="card-header">
    <a name="" id="" class="btn btn-primary" href="crear.php" role="button" >Crear cupones</a>
  </div>
  <div class="card-body">
    <div
      class="table-responsive-sm"
    >
      <table
        class="table"
      >
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
          <tr class="">
            <td scope="row">17</td>
            <td>Erly2024</td>
            <td>500</td>
            <td>05/05/2024</td>
            <td>10/05/2024</td>
            <td>Ninguna</td>
            <td>24/04/2024</td>
            <td>
              <a name="" id="" class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']?>" role="button">Editar</a>
              <a name="" id="" class="btn btn-danger" href="index.php?txtID=<?php echo $registro['id']?>">Eliminar</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
  <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>