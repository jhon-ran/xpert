<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 

//se inicializa variable de sesión
session_start();

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
    //Array para guardar los errores
    $errores= array();
    //Si esta variable existe, se asigna ese valor, de lo contrario se queda
    $txtID = (isset($_POST['txtID']))?$_POST['txtID']:"";

    //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
    //de lo contratrio lo deja en blanco
    $titulo = (isset($_POST["titulo"])? $_POST["titulo"]:"");
    $duracion = (isset($_POST["duracion"])? $_POST["duracion"]:"");
    $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");
    $capacidad = (isset($_POST["capacidad"])? $_POST["capacidad"]:"");
    $idiomas = (isset($_POST["idiomas"])? $_POST["idiomas"]:"");

    //$foto = (isset($_FILES["foto"]['name'])? $_FILES["foto"]['name']:"");

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

    //*******INICIAN VALIDACIONES CAMPO 1********
    //Validar si el título está vacío
    if (empty($titulo)){
        $errores['titulo']= "El título del tour es obligatorio";
    }
    //Validar si el título no tiene menos de 4 caracteres
    if (strlen($titulo) < 5) {
        $errores['titulo'] = "El título debe ser de al menos 5 caracteres";
    }
    //Validar si el título no tiene más de 4 caracteres
    if (strlen($titulo) > 60) {
        $errores['titulo'] = "El título no puede tener más de 60 caracteres";
    }
    //Validar que no tenga caracteres especiales
    if (preg_match('/[=<>|]/', $titulo)) {
        $errores['titulo'] = "El título no puede contener los caracteres especiales = - | < >";
    }

        //******Inicia validación de título existente en bd*****
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $titulo = $_POST['titulo'];
            $id = $_POST['txtID'];
            /*Consulta para ver si título ya existe en la base de datos
            se convierte el titulo a minusculas para poder compararlo
            */
            $sql = "SELECT * FROM tours WHERE LOWER(titulo) = :titulo AND id != :id";
            $stmt = $conn->prepare($sql);
            /*se crea variable para convertir el input a minuscula antes de bindParam
            si se pone directo dispara un error por referencia*/
            $lowerTitulo = strtolower($titulo);
            $stmt->bindParam(':titulo', $lowerTitulo);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            // Si el resultado es verdadero, el título ya existe y se muestra un mensaje de error
            if ($resultado) {
                $errores['titulo'] = "Ese título ya existe en otro tour";
            }
        
        } catch(PDOException $e) {
            echo "Error de conexión: ". $e->getMessage();
        }
        //******Termina validación detítulo existente en bd*****

                //*******INICIAN VALIDACIONES CAMPO  2********
        //Validar si la duración está vacía
        if (empty($duracion)){
            $errores['duracion']= "La duración del tour es obligatoria";
        }
        //Validar si la duración no es un número negativo
        if ($duracion < 0) {
            $errores['duracion'] = "La duración no puede ser negativa";
        }
        //Validar que la duración no sea mayor a 8hrs
        if ($duracion > 14) {
            $errores['duracion'] = "La duración no puede ser mayor a 14hrs";
        }

        //*******INICIAN VALIDACIONES CAMPO  3********
        if (strlen($tipo) > 20) {
            $errores['tipo'] = "El tipo no puede ser mayor a 20 caracteres";
        }
        if (preg_match('/[=<>|]/', $tipo)) {
            $errores['tipo'] = "El tipo no puede contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 4********
        //Validar que la capacidad no sea un número negativo
        if ($capacidad < 0) {
            $errores['capacidad'] = "La capacidad no puede ser negativa";
        }
        //Validar que la capacidad no sea mayor a 50
        if ($capacidad > 50) {
            $errores['capacidad'] = "La capacidad no puede ser mayor a 50";
        }

        //*******INICIAN VALIDACIONES CAMPO 5********
        if (strlen($idiomas) > 20) {
            $errores['idiomas'] = "Idiomas no puede tener más de 20 caracteres";
        }
        if (preg_match('/[=<>|]/', $idiomas)) {
            $errores['idiomas'] = "Idiomas no pueden contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 6********
        /*if (!in_array(pathinfo($foto, PATHINFO_EXTENSION), ['jpeg', 'jpg', 'png'])) {
            $errores['foto'] = "La foto solo puede ser un archivo JPEG o PNG.";
        }
        */

        //*******INICIAN VALIDACIONES CAMPO 7********
        if (strlen($vistaGeneral) > 1100) {
            $errores['vistaGeneral'] = "La vista general no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $vistaGeneral)) {
            $errores['vistaGeneral'] = "La vista general no puede contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 8********
        if (strlen($destacado) > 1100) {
            $errores['destacado'] = "Destacado no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $destacado)) {
            $errores['destacado'] = "Destacado no puede contener los caracteres especiales = - | < >";
        }
        
        //*******INICIAN VALIDACIONES CAMPO 9********
        if (strlen($itinerario) > 1100) {
            $errores['itinerario'] = "El itinerario no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $itinerario)) {
            $errores['itinerario'] = "El itinerario no puede contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 10********
        if (strlen($incluye) > 1100) {
            $errores['incluye'] = "Lo que incluye no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $incluye)) {
            $errores['incluye'] = "Lo que incluye no puede contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 11********
        if (strlen($ubicacion) > 25) {
            $errores['ubicacion'] = "La ubicación no puede tener más de 25 caracteres";
        }

        //*******INICIAN VALIDACIONES CAMPO 12********
        if (strlen($queTraer) > 1100) {
            $errores['queTraer'] = "Qué traer no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $queTraer)) {
            $errores['queTraer'] = "Qué traer no puede contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 13********
        if (strlen($infoAdicional) > 1100) {
            $errores['infoAdicional'] = "La información adicional no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $infoAdicional)) {
            $errores['infoAdicional'] = "La información adicional no puede contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 14********
        if (strlen($polCancel) > 1100) {
            $errores['polCancel'] = "Las políticas de cancelación no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $polCancel)) {
            $errores['polCancel'] = "Las políticas de cancelación no pueden contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 15********
        if (strlen($actividades) > 1100) {
            $errores['actividades'] = "Las actividades no puede tener más de 1100 caracteres";
        }
        if (preg_match('/[=<>|]/', $actividades)) {
            $errores['actividades'] = "Las actividades no pueden contener los caracteres especiales = - | < >";
        }

        //*******INICIAN VALIDACIONES CAMPO 16********
        //Validar que no esté vacio el campo de si incliye transportación
        if (empty($incluyeTransporte)){
            $errores['incluyeTransporte']= "El campo incluye transportación es obligatorio";
        }
        //*******INICIAN VALIDACIONES CAMPO 17********
        if (strlen($transporte) > 25) {
            $errores['transporte'] = "El tipo de transporte no puede tener más de 25 caracteres";
        }
        if (preg_match('/[=<>|]/', $transporte)) {
            $errores['transporte'] = "El tipo de transporte no pueden contener los caracteres especiales = - | < >";
        }
        //*******INICIAN VALIDACIONES CAMPO 18********
        /*
        if (strlen($staff) > 25) {
            $errores['staff'] = "El staff no puede tener más de 25 caracteres";
        }
        if (preg_match('/[=<>|]/', $staff)) {
            $errores['staff'] = "El staff no pueden contener los caracteres especiales = - | < >";
        }
        */

        //*******INICIAN VALIDACIONES CAMPO 19********
        //Validar si el precio está vacío
        if (empty($precioBase)){
            $errores['precioBase']= "El precio del tour es obligatorio";
        }
        //Validar si el precio no es un número negativo
        if ($precioBase < 0) {
            $errores['precioBase'] = "El precio no puede ser negativo";
        }
        //*******INICIAN VALIDACIONES CAMPO 20********
        if ($descuento >= $precioBase) {
            $errores['descuento'] = "El descuento no puede ser igual o mayor al precio del tour";
        }
        /*******INICIAN VALIDACIONES CAMPO 21********/
        if (strlen($redes) > 25) {
            $errores['redes'] = "Las redes no pueden tener más de 25 caracteres";
        }
        

    //Imprimir errores en pantalla si los hay
    foreach($errores as $error){
        $error;
    }

    if(empty($errores)){
        //Conexion a la base de datos
            try{
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
                
                //Si campo vacío, se conviertr a Null para evitar error: Integrity constraint violation: 1452
                if($transporte==""){
                    $transporte = null;
                    $sentencia->bindParam(":transporte",$transporte);
                }

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

                    //******Inicia código para eliminar foto de carpeta tours******
                    //buscar el archivo relacionado con la foto. Si existe se ejecuta la sentencia
                    $sentencia = $conexion->prepare("SELECT foto FROM tours WHERE id=:id");
                    $sentencia->bindParam(":id",$txtID);
                    $sentencia->execute();
                    //se recupera solo un registro asosiado al id
                    $foto_tour = $sentencia->fetch(PDO::FETCH_LAZY);

                    //Si existe ese archivo de la foto o diferente de vacío
                    if(isset($foto_tour["foto"]) && $foto_tour["foto"]!=""){
                        //Si el archivo existe dentro de la carpeta actual
                        if(file_exists("./".$foto_tour["foto"])){
                            //Se elimina el archivo
                            unlink("./".$foto_tour["foto"]);
                        }
                    }
                    //******Termina código para eliminar foto de carpeta tours******

                    //Sentencia para actualizar foto en BD
                    $sentencia = $conexion->prepare("UPDATE tours SET foto=:foto WHERE id=:id");
                    //Se actualiza en BD el nombre de archivo
                    $sentencia->bindParam(":foto",$nombreArchivo_foto);
                    $sentencia->bindParam(":id",$txtID);
                    $sentencia->execute();
                }
                $mensaje="Tour modificado";
                //******Termina código para adjuntar foto******
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
    //query para obtener los nombres del staff y excluir a los que sean de tipo conductor. Explicación:
    /*SELECT s.*: Selecciona todas las columnas de la tabla staff. Se utiliza el alias s para abreviar.
    FROM staff s: Especifica la tabla staff como la tabla principal y le da el alias s.
    JOIN tipo_staff ts ON s.id_tipo_staff = ts.id: Une la tabla tipo_staff con la tabla staff utilizando la relación de clave foránea. Se usa el alias ts para tipo_staff.
    WHERE ts.tipo != 'conductor': Filtra los resultados para excluir los registros donde el valor de la columna tipo en tipo_staff sea 'conductor'.
    */
    $sentencia = $conexion->prepare("SELECT s.*
    FROM staff s
    JOIN tipo_staff ts ON s.id_tipo_staff = ts.id
    WHERE ts.tipo != 'conductor';
    ");
    $sentencia->execute();
    $nombres = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //query para obtener los vehiculos registrados de tabla vehiculo
    $sentencia = $conexion->prepare("SELECT * FROM vehiculo");
    $sentencia->execute();
        //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
    $vehiculos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector

//******Termina código para actualizar registro******
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    <!--Nuevo look inicia-->
    <header class="text-center">
        <h1>Editar tour</h1>
    </header>

        <div class="row">
        <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Información del tour</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="editar.php" id="editarTours" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="txtID" class="form-label">ID</label>
                    <input type="text" class="form-control" value="<?php echo $txtID;?>" name="txtID" id="txtID" aria-describedby="helpId" readonly placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" value="<?php echo $titulo;?>" name="titulo" id="titulo" oninput="validateTitulo()" aria-describedby="helpId" placeholder="Campo obligatorio" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                    <span id="errorTitulo" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['titulo'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['titulo']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <!--Inicia input group para agrupar campos en una misma línea-->
                <div class="input-group">
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="duracion" class="form-label">Duración en horas</label>
                        <input type="number" class="form-control" name="duracion" id="duracion" oninput="validateDuracion()" value="<?php echo $duracion;?>" aria-describedby="helpId" placeholder="" required/>
                        <!--Se llama mensaje de error de validacion de ../../js/validarDuracion.js -->
                        <span id="errorDuracion" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['duracion'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['duracion']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="tipo" class="form-label">Tipo</label>
                        <input type="text" class="form-control" name="tipo" id="tipo" value="<?php echo $tipo;?>" aria-describedby="helpId" placeholder=""/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['tipo'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['tipo']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                </div>
                <!--Inicia input group para agrupar campos en una misma línea-->
                <div class="input-group">
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="capacidad" class="form-label">Capacidad máxima</label>
                        <input type="number" class="form-control" name="capacidad" id="capacidad" oninput="validateCapacidad()" value="<?php echo $capacidad;?>"aria-describedby="helpId" placeholder="" required/>
                        <!--Se llama mensaje de error de validacion de ../../js/validarCapacidad.js -->
                        <span id="errorCapacidad" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['capacidad'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['capacidad']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="idiomas" class="form-label">Idiomas</label>
                        <input
                            type="text" class="form-control" name="idiomas" id="idiomas" value="<?php echo $idiomas;?>" aria-describedby="helpId" placeholder=""/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['idiomas'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['idiomas']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                </div>
                <!--Termina input group para agrupar campos en una misma línea-->
                <div class="mb-3">
                    <img src="<?php echo $foto;?>" class="img-fluid rounded-top" alt="Foto tour"/>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input
                        type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="vistaGenera">Vista general</label>
                    <textarea class="form-control" name="vistaGeneral" id="vistaGeneral" oninput="validateVistaGeneral()" rows="3"><?php echo $vistaGeneral;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarVistaGeneraljs -->
                    <span id="errorVistaGeneral" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['vistaGeneral'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['vistaGeneral']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="destacado">Destacado</label>
                    <textarea class="form-control" name="destacado" id="destacado" oninput="validateDestacado()" rows="3"><?php echo $destacado;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarDestacadojs -->
                    <span id="errorDestacado" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['destacado'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['destacado']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="itinerario">Itinerario</label>
                    <textarea class="form-control" name="itinerario" id="itinerario" oninput="validateItinerario()" rows="3"><?php echo $itinerario;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarItinerario.js -->
                    <span id="errorItinerario" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['itinerario'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['itinerario']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="incluye">Incluye</label>
                    <textarea class="form-control" name="incluye" id="incluye" oninput="validateIncluye()" rows="3"><?php echo $incluye;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarIncluye.js -->
                    <span id="errorIncluye" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['incluye'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['incluye']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    
                </div>
                <div class="mb-3">
                    <label for="ubicacion" class="form-label">Ubicación</label>
                    <input
                        type="text" class="form-control" name="ubicacion" id="ubicacion" value="<?php echo $ubicacion;?>" aria-describedby="helpId" placeholder=""/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['ubicacion'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['ubicacion']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                        
                </div>
                <div class="mb-3">
                    <label for="queTraer">Qué traer</label>
                    <textarea class="form-control" name="queTraer" id="queTraer" oninput="validateQueTraer()" rows="3"><?php echo $queTraer;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarQueTraer.js -->
                    <span id="errorQueTraer" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['queTraer'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['queTraer']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="infoAdicional">Información adicional</label>
                    <textarea class="form-control" name="infoAdicional" id="infoAdicional" oninput="validateInfoAdicional()" rows="3"><?php echo $infoAdicional;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarInfoAdicional.js -->
                    <span id="errorInfoAdicional" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['infoAdicional'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['infoAdicional']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="polCancel">Política de cancelación</label>
                    <textarea class="form-control" name="polCancel" id="polCancel" oninput="validatePolCancel()" rows="3"><?php echo $polCancel;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarPolCancel.js -->
                    <span id="errorPolCancel" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['polCancel'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['polCancel']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="actividades">Actividades para hacer</label>
                    <textarea class="form-control" name="actividades" id="actividades" oninput="validateActividades()" rows="3"><?php echo $actividades;?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarInfoAdicional.js -->
                    <span id="errorActividades" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['actividades'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['actividades']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Actualmente <b><?php echo $incluyeTransporte;?></b> incluye transporte</label>
                </div>
                <!--Inicia input group para agrupar campos en una misma línea-->
                <div class="input-group">
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="incluyeTransporte" class="form-label">¿Incluye transporte?</label>
                        <select class="form-select form-select" name="incluyeTransporte" id="incluyeTransporte">
                            <!--<option value="" selected>Seleccione una opción</option>-->
                            <option value="<?php echo $incluyeTransporte;?>"><?php echo $incluyeTransporte;?></option>
                            <option value="sí">Sí</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="transporte" class="form-label">Vehículo</label>
                        <select class="form-select form-select-sm" name="transporte" id="transporte" onclick="validateTipoStaff()">
                            <option value="" selected>Seleccione una opción</option>
                                <?php foreach($vehiculos as $vehiculo){ ?>
                                        <option <?php echo ($transporte == $vehiculo['id'])?"selected":"";?> value="<?php echo $vehiculo['id']; ?>"><?php echo $vehiculo['modelo']; ?></option>
                                <?php }?>
                        </select>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['transporte'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['transporte']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                </div>
                <!--Inicia input group para agrupar campos en una misma línea-->
                <div class="input-group">
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="staff" class="form-label">Staff a cargo</label>

                        <!--<input type="text" class="form-control" name="staff" id="staff" value="<?php echo $staff;?>" aria-describedby="helpId" placeholder=""/>-->

                        <select class="form-select form-select-sm" name="staff" id="staff" onclick="validateTipoStaff()"required>
                            <option value="" selected>Seleccione una opción</option>
                                <?php foreach($nombres as $nombre){ ?>
                                    <option <?php echo ($staff == $nombre['id'])?"selected":"";?> value="<?php echo $nombre['id']; ?>"><?php echo $nombre["nombre"], ' ', $nombre["apellidos"]; ?></option>
                                <?php }?>
                    </select>

                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['staff'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['staff']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="precioBase" class="form-label">Precio desde</label>
                        <input type="number" class="form-control" name="precioBase" id="precioBase" oninput="validatePrecioBase()" value="<?php echo $precioBase;?>" aria-describedby="helpId" placeholder="" required/>
                        <!--Se llama mensaje de error de validacion de ../../js/validarPrecioBase.js -->
                        <span id="errorPrecioBase" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['precioBase'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['precioBase']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                </div>
                <!--Inicia input group para agrupar campos en una misma línea-->
                <div class="input-group">
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="descuento" class="form-label">Descuento</label>
                        <input type="number" class="form-control" name="descuento" id="descuento" value="<?php echo $descuento;?>" aria-describedby="helpId" placeholder="" required/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['descuento'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['descuento']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="redes" class="form-label">Redes sociales</label>
                        <input
                            type="text" class="form-control" name="redes" id="redes" value="<?php echo $redes;?>" aria-describedby="helpId" placeholder=""/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['redes'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['redes']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                </div>
                <button type="submit" id='submitBtn' class="btn btn-success">Actualizar</button>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </form>
            </div>
        </div>
        <!--<div class="card-footer text-muted"></div>-->
    </div>
</div>
    <!--Nuevo look termina-->



<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>
<!-- Se llaman validaciones de campos desde carpeta js/-->
<!-- Campo 1 js/-->
<script src="../../js/validarTitulo.js"> </script>
<!-- Campo 2-->
<script src="../../js/validarDuracion.js"> </script>
<!-- Campo 4-->
<script src="../../js/validarCapacidad.js"> </script>
<!-- Campo 7-->
<script src="../../js/validarVistaGeneral.js"> </script>
<!-- Campo 8-->
<script src="../../js/validarDestacado.js"> </script>
<!-- Campo 9-->
<script src="../../js/validarItinerario.js"> </script>
<!-- Campo 10-->
<script src="../../js/validarIncluye.js"> </script>
<!-- Campo 12-->
<script src="../../js/validarQueTraer.js"> </script>
<!-- Campo 13-->
<script src="../../js/validarInfoAdicional.js"> </script>
<!-- Campo 14-->
<script src="../../js/validarPolCancel.js"> </script>
<!-- Campo 15-->
<script src="../../js/validarActividades.js"> </script>
<!-- Campo 19-->
<script src="../../js/validarPrecioBase.js"> </script>