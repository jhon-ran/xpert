<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
if($_POST){
        //Array para guardar los errores
        $errores= array();

        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        //Campo 1
        $titulo = (isset($_POST["titulo"])? $_POST["titulo"]:"");
        //Campo 2
        $duracion = (isset($_POST["duracion"])? $_POST["duracion"]:"");
        //Campo 3
        $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");
        //Campo 4
        $capacidad = (isset($_POST["capacidad"])? $_POST["capacidad"]:"");
        //Campo 5
        $idiomas = (isset($_POST["idiomas"])? $_POST["idiomas"]:"");
        //Campo 6
        //Para las fotos y pdfs hay que darle el parametro 'name'
        $foto = (isset($_FILES["foto"]['name'])? $_FILES["foto"]['name']:"");
        //Campo 7
        $vistaGeneral = (isset($_POST["vistaGeneral"])? $_POST["vistaGeneral"]:"");
        //Campo 8
        $destacado = (isset($_POST["destacado"])? $_POST["destacado"]:"");
        //Campo 9
        $itinerario = (isset($_POST["itinerario"])? $_POST["itinerario"]:"");
        //Campo 10
        $incluye = (isset($_POST["incluye"])? $_POST["incluye"]:"");
        //Campo 11
        $ubicacion = (isset($_POST["ubicacion"])? $_POST["ubicacion"]:"");
        //Campo 12
        $queTraer = (isset($_POST["queTraer"])? $_POST["queTraer"]:"");
        //Campo 13
        $infoAdicional = (isset($_POST["infoAdicional"])? $_POST["infoAdicional"]:"");
        //Campo 14
        $polCancel = (isset($_POST["polCancel"])? $_POST["polCancel"]:"");
        //Campo 15
        $actividades = (isset($_POST["actividades"])? $_POST["actividades"]:"");
        //Campo 16
        $incluyeTransporte = (isset($_POST["incluyeTransporte"])? $_POST["incluyeTransporte"]:"");
        //Campo 17
        $transporte = (isset($_POST["transporte"])? $_POST["transporte"]:"");
        //Campo 18
        $staff = (isset($_POST["staff"])? $_POST["staff"]:"");
        //Campo 19
        $precioBase = (isset($_POST["precioBase"])? $_POST["precioBase"]:"");
        //Campo 20
        $descuento = (isset($_POST["descuento"])? $_POST["descuento"]:"");
        //Campo 21
        $redes = (isset($_POST["redes"])? $_POST["redes"]:"");

        //Si los datos están, se llena el array con los mensajes de errores
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
        //La foto no puede estar vacía
        if (empty($foto)) {
            $errores['foto'] = "La foto no puede estar vacía";
        }
        //Validación de formato de archivo aceptado
        if ($foto != null){
            if (!in_array(pathinfo($foto, PATHINFO_EXTENSION), ['jpeg', 'jpg', 'png'])) {
                $errores['foto'] = "La foto solo puede ser un archivo JPEG o PNG.";
        }    
        }

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
            $errores['actividades'] = "actividades no pueden contener los caracteres especiales = - | < >";
        }

        //*******Inicia validaciones campo 16********
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
        if (strlen($staff) > 25) {
            $errores['staff'] = "El staff no puede tener más de 25 caracteres";
        }
        if (preg_match('/[=<>|]/', $staff)) {
            $errores['staff'] = "El staff no pueden contener los caracteres especiales = - | < >";
        }

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

    <!--Nuevo look inicia-->
    <header class="text-center">
            <h1>Crear tour</h1>
    </header>
    <div class="row">
    <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Información del tour</div>
        <div class="card-body">
                <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="crear.php" id="crearTours" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" oninput="validateTitulo()" aria-describedby="helpId" placeholder="Introduzca título de tour" value="<?php echo isset($titulo) ? $titulo : ''; ?>" required/>
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
                        <input type="number" class="form-control" name="duracion" id="duracion" oninput="validateDuracion()" aria-describedby="helpId" placeholder="Introduzca la duración estimada" value="<?php echo isset($duracion) ? $duracion : ''; ?>" required/>
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
                        <input type="text" class="form-control" name="tipo" id="tipo" aria-describedby="helpId" placeholder="" value="<?php echo isset($tipo) ? $tipo : ''; ?>"/>
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
                        <input type="number" class="form-control" name="capacidad" id="capacidad" oninput="validateCapacidad()" aria-describedby="helpId" placeholder="" value="<?php echo isset($capacidad) ? $capacidad : ''; ?>" required/>
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
                        <input type="text" class="form-control" name="idiomas" id="idiomas" aria-describedby="helpId" placeholder="" value="<?php echo isset($idiomas) ? $idiomas : ''; ?>"/>
                        <!--Inicio envio de mensaje de error-->
                        <?php if (isset($errores['idiomas'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['idiomas']; ?></div>
                        <?php endif; ?>
                        <!--Fin envio de mensaje de error-->
                    </div>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="" required/>
                        <!--Inicio envio de mensaje de error-->
                        <?php if (isset($errores['foto'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['foto']; ?></div>
                        <?php endif; ?>
                        <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="vistaGenera">Vista general</label>
                    <textarea class="form-control" name="vistaGeneral" id="vistaGeneral" oninput="validateVistaGeneral()" rows="3"><?php echo isset($vistaGeneral) ? $vistaGeneral : ''; ?></textarea>
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
                    <textarea class="form-control" name="destacado" id="destacado" oninput="validateDestacado()" rows="3"><?php echo isset($destacado) ? $destacado : ''; ?></textarea>
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
                    <textarea class="form-control" name="itinerario" id="itinerario" oninput="validateItinerario()" rows="3"><?php echo isset($itinerario) ? $itinerario : ''; ?></textarea>
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
                    <textarea class="form-control" name="incluye" id="incluye" oninput="validateIncluye()" rows="3"><?php echo isset($incluye) ? $incluye : ''; ?></textarea>
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
                    <input type="text" class="form-control" name="ubicacion" id="ubicacion" aria-describedby="helpId" placeholder="" value="<?php echo isset($ubicacion) ? $ubicacion : ''; ?>"/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['ubicacion'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['ubicacion']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="queTraer">Qué traer</label>
                    <textarea class="form-control" name="queTraer" id="queTraer" oninput="validateQueTraer()" rows="3"><?php echo isset($queTraer) ? $queTraer : ''; ?></textarea>
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
                    <textarea class="form-control" name="infoAdicional" id="infoAdicional" oninput="validateInfoAdicional()" rows="3"><?php echo isset($infoAdicional) ? $infoAdicional : ''; ?></textarea>
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
                    <textarea class="form-control" name="polCancel" id="polCancel" oninput="validatePolCancel()" rows="3"><?php echo isset($polCancel) ? $polCancel : ''; ?></textarea>
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
                    <textarea class="form-control" name="actividades" id="actividades" oninput="validateActividades()" rows="3"><?php echo isset($actividades) ? $actividades : ''; ?></textarea>
                    <!--Se llama mensaje de error de validacion de ../../js/validarInfoAdicional.js -->
                    <span id="errorActividades" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['actividades'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['actividades']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <!--Inicia input group para agrupar campos en una misma línea-->
                <div class="input-group">
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="incluyeTransporte" class="form-label">¿Transportación?</label>
                        <select class="form-select form-select" name="incluyeTransporte" id="incluyeTransporte" required>
                            <option value="" selected>Seleccione una opción</option>
                            <option value="sí">Sí</option>
                            <option value="no">No</option>
                        </select>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['incluyeTransporte'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['incluyeTransporte']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;" id="transporte">
                        <label for="transporte" class="form-label">Tipo de transporte</label>
                        <input type="text" class="form-control" name="transporte" id="transporte" aria-describedby="helpId" placeholder="" value="<?php echo isset($transporte) ? $transporte : ''; ?>"/>
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
                        <input type="text" class="form-control" name="staff" id="staff" aria-describedby="helpId" placeholder="" value="<?php echo isset($staff) ? $staff : ''; ?>"/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['staff'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['staff']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="precioBase" class="form-label">Precio desde</label>
                        <input type="number" class="form-control" name="precioBase" id="precioBase" oninput="validatePrecioBase()" aria-describedby="helpId" placeholder="" value="<?php echo isset($precioBase) ? $precioBase : ''; ?>" required/>
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
                        <input type="number" class="form-control" name="descuento" id="descuento" aria-describedby="helpId" placeholder="Introduzca cero si no hay descuento" value="<?php echo isset($descuento) ? $descuento : ''; ?>" required/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['descuento'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['descuento']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                    <div class="mb-3 mx-auto" style="width:48%;">
                        <label for="redes" class="form-label">Redes sociales</label>
                        <input type="text" class="form-control" name="redes" id="redes" aria-describedby="helpId" placeholder="" value="<?php echo isset($redes) ? $redes : ''; ?>"/>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['redes'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['redes']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                    </div>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-success">Crear</button>
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
<!-- Campo 1-->
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