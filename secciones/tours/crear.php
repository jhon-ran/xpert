<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
if($_POST){
        //print_r($_POST);
        //Array para guardar los errores
        $errores= array();

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

        //Si los datos están, se llena el array con los mensajes de errores
        //Validar si el título está vacío
        if (empty($titulo)){
            $errores['titulo']= "El título del tour es obligatorio";
        }
        //Validar si el título no tiene menos de 4 caracteres
        if (strlen($titulo) < 5) {
            $errores['titulo'] = "El título debe ser de al menos 5 caracteres";
        }
        //Validar si el título no tiene más de 4 caracteres
        if (strlen($titulo) > 50) {
            $errores['titulo'] = "El título no puede tener más de 50 caracteres";
        }


        //Validar si la duración está vacía
        if (empty($duracion)){
            $errores['duracion']= "La duración del tour es obligatoria";
        }
        //Validar si la duración no es un número negativo
        if ($duracion < 0) {
            $errores['duracion'] = "La duración no puede ser un número negativo";
        }
        //Validar que la duración no sea mayor a 8hrs
        if ($duracion > 8) {
            $errores['duracion'] = "La duración no puede ser mayor a 8hrs";
        }
    


        //Validar si el precio está vacío
        if (empty($precioBase)){
            $errores['precioBase']= "El precio del tour es obligatorio";
        }
        //Validar si el precio no es un número negativo
        if ($duracion < 0) {
            $errores['precioBase'] = "La precio no puede ser un número negativo";
        }

        //******Inicia validación de título existente en bd*****
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $titulo = $_POST['titulo'];
            /*Consulta para ver si título ya existe en la base de datos
            se convierte el titulo a minusculas para poder compararlo
            */
            $sql = "SELECT * FROM tours WHERE LOWER(titulo) = :titulo";
            $stmt = $conn->prepare($sql);
            /*se crea variable para convertir el input a minuscula antes de bindParam
            si se pone directo dispara un error por referencia*/
            $lowerTitulo = strtolower($titulo);
            $stmt->bindParam(':titulo', $lowerTitulo);
            $stmt->execute();
        
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            // Si el resultado es verdadero, el título ya existe y se muestra un mensaje de error
            if ($resultado) {
                $errores['titulo'] = "Ese título ya existe en otro tour";
            }
        
        } catch(PDOException $e) {
            echo "Error de conexión: ". $e->getMessage();
        }
    
        //******Termina título de email existente en bd*****

        //Imprimir errores en pantalla si los hay
        foreach($errores as $error){
            $error;
        }

        //Si no hay errores (array de errores vacio)
        if(empty($errores)){
            //Conexion a la base de datos
            try{
            //Preparar la inseción de los datos enviados por POST
            $sentencia = $conexion->prepare("INSERT INTO tours(id,titulo,duracion,tipo,capacidad,idiomas,foto,vistaGeneral,destacado,itinerario,incluye,ubicacion,queTraer,infoAdicional,polCancel,actividades,incluyeTransporte,transporte,staff,precioBase,descuento,redes) 
            VALUES (null, :titulo, :duracion, :tipo, :capacidad, :idiomas, :foto, :vistaGeneral, :destacado, :itinerario, :incluye, :ubicacion,:queTraer, :infoAdicional, :polCancel, :actividades, :incluyeTransporte, :transporte, :staff, :precioBase, :descuento, :redes)" );
            //Asignar los valores que vienen del formulario (POST)
            $sentencia->bindParam(":titulo",$titulo);
            $sentencia->bindParam(":duracion",$duracion);
            $sentencia->bindParam(":tipo",$tipo);
            $sentencia->bindParam(":capacidad",$capacidad);
            $sentencia->bindParam(":idiomas",$idiomas);


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
            //Se ejecuta la sentencia con los valores de param asignados
            $sentencia->execute();
            //Mensaje de confirmación de creado que activa Sweet Alert 2
            $mensaje="Tour creado";
            //Redirecionar después de crear a la lista de tours con link de Sweet Alert 2
            header("Location:index.php?mensaje=".$mensaje);

        }catch(Exception $ex){
            echo "Error de conexión:".$ex->getMessage();
        }
        }else {
            //La variable para mensaje de exito se actualiza a false si no se pudo insertar
            $succes=false;
        }

    }
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<!--
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
-->

    <!--Nuevo look inicia-->
    <h2>Crear tour</h2>
    <div class="card">
        <div class="card-header">Información del tour</div>
        <div class="card-body">
                <!--Inicio envio de mensaje de error-->
                <?php if(isset($error)) { ?>
                    <?php foreach($errores as $error){ ?>
                        <div class="alert alert-danger" role="alert">
                            <strong><?php echo $error;?></strong>
                        </div>
                    <?php }?>
                <?php }?>
            <!--Fin envio de mensaje de error-->
            <form action="crear.php" id="crearTours" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="duracion" class="form-label">Duración en horas</label>
                    <input type="number" class="form-control" name="duracion" id="duracion" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <input type="text" class="form-control" name="tipo" id="tipo" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="capacidad" class="form-label">Capacidad máxima</label>
                    <input
                        type="number" class="form-control" name="capacidad" id="capacidad" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="idiomas" class="form-label">Idiomas</label>
                    <input
                        type="text" class="form-control" name="idiomas" id="idiomas" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input
                        type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="vistaGenera">Vista general</label>
                    <textarea class="form-control" name="vistaGeneral" id="vistaGeneral" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="destacado">Destacado</label>
                    <textarea class="form-control" name="destacado" id="destacado" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="itinerario">Itinerario</label>
                    <textarea class="form-control" name="itinerario" id="itinerario" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="incluye">Incluye</label>
                    <textarea class="form-control" name="incluye" id="incluye" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="ubicacion" class="form-label">Ubicación</label>
                    <input
                        type="text" class="form-control" name="ubicacion" id="ubicacion" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="queTraer">Qué traer</label>
                    <textarea class="form-control" name="queTraer" id="queTraer" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="infoAdicional">Información adicional</label>
                    <textarea class="form-control" name="infoAdicional" id="infoAdicional" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="polCancel">Política de cancelación</label>
                    <textarea class="form-control" name="polCancel" id="polCancel" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="actividades">Actividades para hacer</label>
                    <textarea class="form-control" name="actividades" id="actividades" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="incluyeTransporte" class="form-label">Incluye transportación</label>
                    <select class="form-select form-select-sm" name="incluyeTransporte" id="incluyeTransporte">
                        <option value="" selected>Seleccione una opción</option>
                        <option value="sí">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="transporte" class="form-label">Tipo de transporte</label>
                    <input
                        type="text" class="form-control" name="transporte" id="transporte" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="staff" class="form-label">Staff a cargo</label>
                    <input
                        type="text" class="form-control" name="staff" id="staff" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="precioBase" class="form-label">Precio desde</label>
                    <input type="number" class="form-control" name="precioBase" id="precioBase" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="descuento" class="form-label">Descuento</label>
                    <input type="number" class="form-control" name="descuento" id="descuento" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="redes" class="form-label">Redes sociales</label>
                    <input
                        type="text" class="form-control" name="redes" id="redes" aria-describedby="helpId" placeholder=""/>
                </div>
                <button type="submit" class="btn btn-success">Crear</button>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </form>

        </div>
        <div class="card-footer text-muted"></div>
    </div>
    <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>