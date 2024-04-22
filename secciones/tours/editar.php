<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
    //Si esta variable existe, se asigna ese valor, de lo contrario se queda
    $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
    //Se prepara sentencia para editar dato seleccionado (id)
    $sentencia = $conexion->prepare("SELECT * FROM tours WHERE id=:id");
    //Asignar los valores que vienen del método GET (id seleccionado por params)
    $sentencia->bindParam(":id",$txtID);
    //Se ejecuta la sentencia con el valor asignado para borrar
    $sentencia->execute();
    //Popular el formulario con los valores de 1 registro
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    //Asignar los valores que vienen del formulario (POST)
    $titulo = $registro["titulo"];
    $duracion = $registro["duracion"];
    $tipo = $registro["tipo"];
    $capacidad = $registro["capacidad"];
    $idiomas = $registro["idiomas"];
    //Recibir parámetro de la foto
    $foto = $registro["foto"];
    //Se continuan los bindParam después de foto
    $vistaGeneral = $registro["vistaGeneral"];
    $destacado = $registro["destacado"];
    $itinerario = $registro["itinerario"];
    $incluye = $registro["incluye"];
    $ubicacion = $registro["ubicacion"];
    $queTraer = $registro["queTraer"];
    $infoAdicional = $registro["infoAdicional"];
    $polCancel = $registro["polCancel"];
    $actividades = $registro["actividades"];
    $incluyeTransporte = $registro["incluyeTransporte"];
    $transporte = $registro["transporte"];
    $staff = $registro["staff"];
    $precioBase = $registro["precioBase"];
    $descuento = $registro["descuento"];
    $redes = $registro["redes"];

    //print_r($restricciones);
}
//******Termina código para recibir registro******

//******Empieza código para actualizar registro******
if($_POST){
    //print_r($_POST);
    //Si esta variable existe, se asigna ese valor, de lo contrario se queda
    $txtID = (isset($_POST['txtID']))?$_POST['txtID']:"";

    //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
    //de lo contratrio lo deja en blanco
    $titulo = (isset($_POST["titulo"])? $_POST["titulo"]:"");
    $duracion = (isset($_POST["duracion"])? $_POST["duracion"]:"");
    $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");
    $capacidad = (isset($_POST["capacidad"])? $_POST["capacidad"]:"");
    $idiomas = (isset($_POST["idiomas"])? $_POST["idiomas"]:"");

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
    $sentencia = $conexion->prepare("UPDATE tours SET 
    titulo=:titulo,
    duracion=:duracion,
    tipo=:tipo,
    capacidad=:capacidad,
    idiomas=:idiomas,
    vistaGeneral=:vistaGeneral,
    destacado=:destacado,
    itinerario=:itinerario,
    incluye=:incluye,
    ubicacion=:ubicacion,
    queTraer=:queTraer,
    infoAdicional=:infoAdicional,
    polCancel=:polCancel,
    actividades=:actividades,
    incluyeTransporte=:incluyeTransporte,
    transporte=:transporte,
    staff=:staff,
    precioBase=:precioBase,
    descuento=:descuento,
    redes=:redes
    WHERE id=:id");

    //Asignar los valores que vienen del formulario (POST)
    $sentencia->bindParam(":titulo",$titulo);
    $sentencia->bindParam(":duracion",$duracion);
    $sentencia->bindParam(":tipo",$tipo);
    $sentencia->bindParam(":capacidad",$capacidad);
    $sentencia->bindParam(":idiomas",$idiomas);

    //Se continuan los bindParam después de foto
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
    $sentencia->bindParam(":id",$txtID);
    //Se ejecuta la sentencia con los valores de param asignados
    $sentencia->execute();

    //Para las fotos y pdfs hay que darle el parametro 'name'
    $foto = (isset($_FILES["foto"]['name'])? $_FILES["foto"]['name']:"");

    //Redirecionar a la lista de puestos
    //header("Location:index.php");
}
//******Termina código para actualizar registro******
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Editar Tours</h1>

<form action="editar.php" id="editarTours" method="post" enctype="multipart/form-data">
ID:
        <input type="text" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" placeholder="ID"/><br>
        Título:
        <input type="text" name="titulo" id="titulo" value = "<?php echo $titulo;?>"><br>
        Duración:
        <input type="number" name="duracion" id="duracion" value = "<?php echo $duracion;?>"><br>
        Tipo:
        <input type="text" name="tipo" id="tipo" value = "<?php echo $tipo;?>"><br>
        Capacidad:
        <input type="number" name="capacidad" id="capacidad" value = "<?php echo $capacidad;?>"><br>
        Idiomas:
        <input type="text" name="idiomas" id="idiomas" value = "<?php echo $idiomas;?>"><br>
        <label for="foto">Foto:</label>
        <br/>
        <img width="300" src="<?php echo $foto;?>" alt="foto"/>
        
        <input type="file" name="foto" id="foto"><br>
        <!-- Para que se muestren los datos en textarea se deben poner en placeholder-->
        <p>Vista general:</p>
        <textarea name="vistaGeneral" id="vistaGeneral" cols="50" rows="5"><?php echo $vistaGeneral;?></textarea><br>
        <p>Destacado:</p>
        <textarea name="destacado" id="destacado" cols="50" rows="5"><?php echo $destacado;?></textarea><br>
        <p>Itinerario:</p>
        <textarea name="itinerario" id="itinerario" cols="50" rows="5"><?php echo $itinerario;?></textarea><br>
        <p>Incluye:</p>
        <textarea name="incluye" id="incluye" cols="50" rows="5"><?php echo $incluye;?></textarea><br>
        Ubicación del tour:
        <input type="text" name="ubicacion" id="ubicacion" value ="<?php echo $ubicacion;?>"><br>
        <p>Qué traer:</p>
        <textarea name="queTraer" id="queTraer" cols="50" rows="5"><?php echo $queTraer;?></textarea><br>
        <p>Información adicional:</p>
        <textarea name="infoAdicional" id="infoAdicional" cols="50" rows="5"><?php echo $infoAdicional;?></textarea><br>
        <p>Política de cancelación:</p>
        <textarea name="polCancel" id="polCancel" cols="50" rows="5"><?php echo $polCancel;?></textarea><br>
        <p>Actividades para hacer:</p>
        <textarea name="actividades" id="actividades" cols="50" rows="5"><?php echo $actividades;?></textarea><br>
        <label for="incluyeTransporte">Actualmente incluye transporte?</label>
        "<?php echo $incluyeTransporte;?>"
        <br>
        Transportación incluida:
        <select name="incluyeTransporte" id="incluyeTransporte">
            <option value="Sí">Sí</option>
            <option value="No">No</option>
        </select>
        Tipo de transporte:
        <input type="text" name="transporte" id="transporte" value ="<?php echo $transporte;?>"><br>
        Staff encargado:
        <input type="text" name="staff" id="staff" value ="<?php echo $staff;?>"><br>
        Precio desde:
        <input type="number" name="precioBase" id="precioBase" value ="<?php echo $precioBase;?>"><br>
        Descuento:
        <input type="number" name="descuento" id="descuento" value ="<?php echo $descuento;?>"><br>
        Redes sociales:
        <input type="text" name="redes" id="redes" value ="<?php echo $redes;?>"><br>

        <button type="submit">Editar tour</button>
        <a href="index.php">Cancelar</a>
</form>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>