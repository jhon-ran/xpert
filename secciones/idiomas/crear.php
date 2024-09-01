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
        $lengua = (isset($_POST["lengua"])? $_POST["lengua"]:"");

        //Validar que el tipo de staff no esté vacio
        if (empty($lengua)){
                $errores['tipo']= "El nombre del idioma es bligatorio";
        }
        //Validar si el tipo de staff no tiene menos de 4 caracteres
        if (strlen($lengua) < 4) {
                $errores['lengua'] = "Debe tener al menos 4 caracteres";
        }
        //Validar si el nel tipo de staff tiene más de 10 caracteres
        if (strlen($lengua) > 20) {
                $errores['lengua'] = "No puede tener más de 15 caracteres";
        }
        //Validar que el tipo de staff solo tenga letras y num
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ& \s\'\-]+$/", $lengua)) {
        $errores['lengua'] = "El tipo solo puede contener letras";
        }

        //******Inicia validación tipo de staff existente en bd*****

        
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //$email = $_POST['nombre'];
                // Consulta para ver si nombre tipo de staff ya existe en la base de datos
                $sql = "SELECT * FROM idiomas WHERE lengua = :lengua";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':lengua', $lengua);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si el resultado es verdadero, el nombre ya existe y se muestra un mensaje de error
                if ($resultado) {
                        $errores['lengua'] = "Ya existe ese idioma";
                }
                
                } catch(PDOException $e) {
                echo "Error de conexión: ". $e->getMessage();
                }
        //******Termina validación de nombre de cupón existente en bd*****


        //Imprimir los errores
        foreach($errores as $error){
                $error;
           }

        //Si no hay errores (array de errores vacio)
        if(empty($errores)){

                try{
                //Preparar la inseción de los datos enviados por POST
                $sentencia = $conexion->prepare("INSERT INTO idiomas(id,lengua) 
                VALUES (null, :lengua)" );
                
                //Asignar los valores que vienen del formulario (POST)
                //Se convierte el tipo a minusculas antes de enviarlo a la BD con strtolower()
                $sentencia->bindParam(":lengua",strtolower($lengua));
                //Se ejecuta la sentencia con los valores de param asignados
                $sentencia->execute();
                //Mensaje de confirmación de creado que activa Sweet Alert 2
                $mensaje="Idioma creado";
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
<header class="text-center my-5">
    <h1>Añadir idioma</h1>
</header>

<div class="row my-2">
        <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
                <div class="card">
                <div class="card-header">Idioma para tour</div>
                        <div class="card-body">
                                <form action="crear.php" id="crearIdioma" method="post">
                                        <div class="mb-3">
                                                <label for="lengua" class="form-label">Idioma</label>
                                                <input type="text" class="form-control" name="lengua" id="lengua" oninput="validateIdioma()" aria-describedby="helpId" placeholder="Ingrese idioma" value="<?php echo isset($lengua) ? $lengua : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorLengua" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['lengua'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['lengua']; ?></div>
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
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>

<!-- Llama funcion para validar cupones-->
<script src="../../js/validarIdioma.js"> </script>