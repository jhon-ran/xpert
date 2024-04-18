<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<br>
<h1>Bienvenidos al index de usuarios</h1>
<table>
  <tr>
    <th>Nombre</th>
    <th>Apellidos</th>
    <th>Email</th>
    <th>Tipo de usuario</th>
    <th>Acciones</th>
  </tr>
  <tr>
    <td>Norma</td>
    <td>Morales Piña</td>
    <td>pina@gmail.com</td>
    <td>cliente</td>
    <td>Editar | Eliminar </td>
  </tr>
</table>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>