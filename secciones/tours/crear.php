<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Crear Tours</h1>
<form action="crear.php" id="crearTours" method="post">
        Nombre:
        <input type="text" name="nombre" id="nombre"><br>
        Descuento:
        <input type="number" name="descuento" id="descuento"><br>
 
        <p>Restricciones:</p>
        <textarea name="restricciones" id="restricciones" cols="40" rows="5"></textarea><br>
        <button type="submit">Crear tour</button>
        <a href="index.php">Cancelar</a>
</form>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>