<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Bienvenidos al index de cupones</h1>

<a href="crear.php">Crear cupones</a>
<br>
  <br>
<table>
  <tr>
    <th>Nombre</th>
    <th>Descuento</th>
    <th>Inicio de validez</th>
    <th>Termino de validez</th>
    <th>Acciones</th>
  </tr>
  <tr>
    <td>EARLYBIRD</td>
    <td>1000</td>
    <td>20/05/204</td>
    <td>22/05/2024</td>
    <td><a href="">Editar</a> | <a href="">Eliminar</a>  </td>
  </tr>
</table>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>