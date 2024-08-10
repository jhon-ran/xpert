<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
//si no existe la variable de sesión usuario_id, se redirige al index
if($_SESSION["usuario_tipo"]=="cliente" || $_SESSION["usuario_tipo"]=="ventas"){
    header('Location:../../index.php');
    exit();
}
if($_POST){
        //Array para guardar los errores
        $errores= array();

        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        $nombre = (isset($_POST["nombre"])? $_POST["nombre"]:"");
        //Para las fotos y pdfs hay que darle el parametro 'name'
        $foto = (isset($_FILES["foto"]['name'])? $_FILES["foto"]['name']:"");

        //Validar que el tipo de staff no esté vacio
        if (empty($nombre)){
                $errores['nombre']= "La red social es obligatoria";
        }
        //Validar si el tipo de staff no tiene menos de 4 caracteres
        if (strlen($nombre) < 4) {
                $errores['nombre'] = "Debe tener al menos 4 caracteres";
        }
        //Validar si el nel tipo de staff tiene más de 10 caracteres
        if (strlen($nombre) > 10) {
                $errores['nombre'] = "No puede 10 caracteres";
        }
        //Validar que el tipo de staff solo tenga letras y num
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9\s\'\-]+$/", $nombre)) {
        $errores['nombre'] = "Solo puede contener letras y números";
        }

        //******Inicia validación tipo de staff existente en bd*****
        /*
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //$email = $_POST['nombre'];
                // Consulta para ver si nombre tipo de staff ya existe en la base de datos
                $sql = "SELECT * FROM tipo_staff WHERE tipo = :tipo";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':tipo', $tipo);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si el resultado es verdadero, el nombre ya existe y se muestra un mensaje de error
                if ($resultado) {
                        $errores['tipo'] = "Ya existe ese tipo de staff";
                }
                
                } catch(PDOException $e) {
                echo "Error de conexión: ". $e->getMessage();
                }
        
                //******Termina validación de nombre de cupón existente en bd*****
        */

        //Imprimir los errores
        foreach($errores as $error){
                $error;
           }

        //Si no hay errores (array de errores vacio)
        if(empty($errores)){

                try{
                //Preparar la inseción de los datos enviados por POST
                $sentencia = $conexion->prepare("INSERT INTO logos(id,nombre,foto) 
                VALUES (null, :nombre, :foto)" );
                
                //Asignar los valores que vienen del formulario (POST)
                //Se convierte el tipo a minusculas antes de enviarlo a la BD con strtolower()
                $sentencia->bindParam(":nombre",strtolower($nombre));


                //******Inicia código para adjuntar logo******
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
                //Mensaje de confirmación de creado que activa Sweet Alert 2
                $mensaje="Logo creado";
                //Redirecionar después de crear a la lista de tipo de staff con link de Sweet Alert 2
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

<!--Nuevo look empieza-->
<header class="text-center">
            <h1>Añadir logo</h1>
</header>

<div class="row">
        <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
                <div class="card">
                <div class="card-header">Red social</div>
                        <div class="card-body">
                                <form action="crear.php" id="crearLogo" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="nombre" id="nombre" oninput="validateTipoStaff()" aria-describedby="helpId" placeholder="Ingrese nombre de red" value="<?php echo isset($nombre) ? $nombre : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorNombre" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['nombre'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['nombre']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="foto" class="form-label">Logo</label>
                                                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" placeholder="" required/>
                                                        <!--Inicio envio de mensaje de error-->
                                                        <?php if (isset($errores['foto'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['foto']; ?></div>
                                                        <?php endif; ?>
                                                        <!--Fin envio de mensaje de error-->
                                        </div>
                                        <!--Botón de crear inicia inhabilitado para validaciones-->
                                        <button type="submit" id="submitBtn" class="btn btn-success">Añadir</button>
                                        <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                                </form>
                        </div>
                </div>
        </div>
</div>
    <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>
