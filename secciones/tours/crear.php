<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Crear Tours</h1>
<form action="crear.php" id="crearTours" method="post">
        Título:
        <input type="text" name="titulo" id="titulo"><br>
        Duración:
        <input type="number" name="duracion" id="duracion"><br>
        Tipo:
        <input type="text" name="tipo" id="tipo"><br>
        Capacidad:
        <input type="number" name="capacidad" id="capacidad"><br>
        Idiomas:
        <input type="text" name="idiomas" id="idiomas"><br>
        Foto:
        <input type="text" name="foto" id="foto"><br>
        <p>Vista general:</p>
        <textarea name="vistaGeneral" id="vistaGeneral" cols="50" rows="5"></textarea><br>
        <p>Destacado:</p>
        <textarea name="destacado" id="destacado" cols="50" rows="5"></textarea><br>
        <p>Itinerario:</p>
        <textarea name="itinerario" id="itinerario" cols="50" rows="5"></textarea><br>
        <p>Incluye:</p>
        <textarea name="incluye" id="incluye" cols="50" rows="5"></textarea><br>
        Ubicación del tour:
        <input type="text" name="ubicacion" id="ubicacion"><br>
        <p>Qué traer:</p>
        <textarea name="queTraer" id="queTraer" cols="50" rows="5"></textarea><br>
        <p>Información adicional:</p>
        <textarea name="infoAdicional" id="infoAdicional" cols="50" rows="5"></textarea><br>
        <p>Política de cancelación:</p>
        <textarea name="polCancel" id="polCancel" cols="50" rows="5"></textarea><br>
        <p>Actividades para hacer:</p>
        <textarea name="actividades" id="actividades" cols="50" rows="5"></textarea><br>
        Transportación incluida:
        <select name="incluyeTransporte" id="incluyeTransporte">
            <option value="Sí">Sí</option>
            <option value="No">No</option>
        </select>
        Tipo de transporte:
        <input type="text" name="transporte" id="transporte"><br>
        Staff encargado:
        <input type="text" name="staff" id="staff"><br>
        Precio desde:
        <input type="number" name="precioBase" id="precioBase"><br>
        Descuento:
        <input type="number" name="descuento" id="descuento"><br>
        Redes sociales:
        <input type="text" name="redes" id="redes"><br>

        <button type="submit">Crear tour</button>
        <a href="index.php">Cancelar</a>
</form>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>