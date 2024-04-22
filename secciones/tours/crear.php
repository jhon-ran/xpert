<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
if($_POST){
        print_r($_POST);
    
        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        $titulo = (isset($_POST["titulo"])? $_POST["titulo"]:"");
        $duracion = (isset($_POST["duracion"])? $_POST["duracion"]:"");
        $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");
        $capacidad = (isset($_POST["capacidad"])? $_POST["capacidad"]:"");
        $idiomas = (isset($_POST["idiomas"])? $_POST["idiomas"]:"");

        //Para las fotos y pdfs hay que darle el parametro 'name'
        $foto = (isset($_FILES["foto"]['name'])? $_FILES["foto"]['name']:"");

        $vistaGeneral = (isset($_POST["vistaGeneral"])? $_POST["vistaGeneral"]:"");
        $destacado = (isset($_POST["destacado"])? $_POST["destacado"]:"");
        $itinerario = (isset($_POST["itinerario"])? $_POST["itinerario"]:"");
        $incluye = (isset($_POST["incluye"])? $_POST["incluye"]:"");
        $ubicacion = (isset($_POST["ubicacion"])? $_POST["ubicacion"]:"");
        $queTraer = (isset($_POST["queTraer"])? $_POST["queTraer"]:"");
        $infoAdicional = (isset($_POST["infoAdicional"])? $_POST["infoAdicional"]:"");
        $polCancel = (isset($_POST["polCancel"])? $_POST["polCancel"]:"");
        $actividades = (isset($_POST["actividades"])? $_POST["actividades"]:"");
        $incluyeTransporte = (isset($_POST["incluyeTransporte"])? $_POST["incluyeTransporte"]:"");
        $transporte = (isset($_POST["transporte"])? $_POST["transporte"]:"");
        $staff = (isset($_POST["staff"])? $_POST["staff"]:"");
        $precioBase = (isset($_POST["precioBase"])? $_POST["precioBase"]:"");
        $descuento = (isset($_POST["descuento"])? $_POST["descuento"]:"");
        $redes = (isset($_POST["redes"])? $_POST["redes"]:"");

        //Preparar la inseción de los datos enviados por POST
        $sentencia = $conexion->prepare("INSERT INTO tours(id,titulo,duracion,tipo,capacidad,idiomas,foto,vistaGeneral,destacado,itinerario,incluye,ubicacion,queTraer,infoAdicional,polCancel,actividades,incluyeTransporte,transporte,staff,precioBase,descuento,redes) 
        VALUES (null, :titulo, :duracion, :tipo, :capacidad, :idiomas, :foto, :vistaGeneral, :destacado, :itinerario, :incluye, :ubicacion,:queTraer, :infoAdicional, :polCancel, :actividades, :incluyeTransporte, :transporte, :staff, :precioBase, :descuento, :redes)" );
        //Asignar los valores que vienen del formulario (POST)
        $sentencia->bindParam(":titulo",$titulo);
        $sentencia->bindParam(":duracion",$duracion);
        $sentencia->bindParam(":tipo",$tipo);
        $sentencia->bindParam(":capacidad",$capacidad);
        $sentencia->bindParam(":idiomas",$idiomas);

        $sentencia->bindParam(":vistaGeneral",$vistaGeneral);
        $sentencia->bindParam(":destacado",$destacado);
        $sentencia->bindParam(":itinerario",$itinerario); 
        $sentencia->bindParam(":incluye",$incluye);
        $sentencia->bindParam(":ubicacion",$ubicacion);
        $sentencia->bindParam(":queTraer",$queTraer);
        $sentencia->bindParam(":infoAdicional",$infoAdicional);
        $sentencia->bindParam(":polCancel",$polCancel);
        $sentencia->bindParam(":actividades",$actividades);
        $sentencia->bindParam(":incluyeTransporte",$incluyeTransporte);
        $sentencia->bindParam(":transporte",$transporte);
        $sentencia->bindParam(":staff",$staff);
        $sentencia->bindParam(":precioBase",$precioBase);
        $sentencia->bindParam(":descuento",$descuento);
        $sentencia->bindParam(":redes",$redes);

        //******Inicia código para adjuntar foto******
        //Obtenemos tiempo para ir cambiando el nombre y que no se sobre escriba
        $fecha_foto = new DateTime();
        //Crear nuevo nombre de archivo: Si $foto tiene un valor,se crea el nombre con time stamp y el valor de nombre de foto, si no queda vacio
        //Se crea variable para guardar nombre de archivo
        $nombreArchivo_foto = ($foto!='')?$fecha_foto->getTimestamp()."_".$_FILES["foto"]['name']:"";
        //Variable temp para guardar nombre de foto con nombre de archivo binario
        //Se obtiene el nombre temporal del archivo
        $tmp_foto = $_FILES["foto"]['tmp_name'];
        //Si el archivo tmp no está vacio
        if($tmp_foto!=''){
            //Movemos el archivo en direccion predeterminada
            //Esta dirección corresponde a a la carpeta donde estamos ->./ "tours"
            move_uploaded_file($tmp_foto,"./".$nombreArchivo_foto);
        }
        //Se actualiza en BD el nombre de archivo
        $sentencia->bindParam(":foto",$nombreArchivo_foto);
    //******Termina código para adjuntar foto******

        //Se ejecuta la sentencia con los valores de param asignados
        $sentencia->execute();
        //Redirecionar a la lista de puestos
        header("Location:index.php");
    }
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Crear Tours</h1>
<form action="crear.php" id="crearTours" method="post" enctype="multipart/form-data">
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
        <input type="file" name="foto" id="foto"><br>
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