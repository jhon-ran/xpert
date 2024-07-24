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
        $modelo = (isset($_POST["modelo"])? $_POST["modelo"]:"");
        $anio = (isset($_POST["anio"])? $_POST["anio"]:"");
        $placas = (isset($_POST["placas"])? $_POST["placas"]:"");

        //Validar que el tipo de staff no esté vacio
        if (empty($placas)){
                $errores['placas']= "Las placas son obligatorias";
        }
        //Validar si el tipo de staff no tiene menos de 4 caracteres
        if (strlen($placas) < 5) {
                $errores['placas'] = "Debe tener al menos 5 caracteres";
        }
        //Validar si el nel tipo de staff tiene más de 10 caracteres
        if (strlen($placas) > 10) {
                $errores['placas'] = "No puede tener más de 10 caracteres";
        }
        //Validar que el tipo de staff solo tenga letras y num
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9\s\'\-]+$/", $placas)) {
        $errores['placas'] = "Solo puede contener letras y números";
        }

        //******Inicia validación tipo de staff existente en bd*****
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //$email = $_POST['nombre'];
                // Consulta para ver si nombre tipo de staff ya existe en la base de datos
                $sql = "SELECT * FROM vehiculo WHERE placas = :placas";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':placas', $placas);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si el resultado es verdadero, el nombre ya existe y se muestra un mensaje de error
                if ($resultado) {
                        $errores['placas'] = "Ya existen esas placas";
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
                $sentencia = $conexion->prepare("INSERT INTO vehiculo(id,modelo,anio,placas) 
                VALUES (null,:modelo,:anio,:placas)" );
                
                //Asignar los valores que vienen del formulario (POST)
                //Se convierte el tipo a mayusculas antes de enviarlo a la BD con strtolower()
                
                $sentencia->bindParam(":modelo",$modelo);
                $sentencia->bindParam(":anio",$anio);
                $sentencia->bindParam(":placas",strtoupper($placas));
                //Se ejecuta la sentencia con los valores de param asignados
                $sentencia->execute();
                //Mensaje de confirmación de creado que activa Sweet Alert 2
                $mensaje="Vehículo creado";
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
            <h1>Alta de vehículo</h1>
</header>

<div class="row">
        <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
                <div class="card">
                <div class="card-header">Vehículo</div>
                        <div class="card-body">
                                <form action="crear.php" id="crearVehiculo" method="post">
                                        <div class="mb-3">
                                                <label for="modelo" class="form-label">Modelo</label>
                                                <input type="text" class="form-control" name="modelo" id="modelo" oninput="validateNombre()" aria-describedby="helpId" placeholder="Ingrese modelo" value="<?php echo isset($tipo) ? $tipo : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorNombre" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['placas'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['placas']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="anio" class="form-label">Año</label>
                                                <input type="number" class="form-control" name="anio" id="anio" oninput="validateNombre()" aria-describedby="helpId" placeholder="Ingrese año" value="<?php echo isset($tipo) ? $tipo : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorNombre" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['tipo'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['tipo']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="placas" class="form-label">Placas</label>
                                                <input type="text" class="form-control" name="placas" id="placas" oninput="validateNombre()" aria-describedby="helpId" placeholder="Ingrese placas" value="<?php echo isset($tipo) ? $tipo : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorNombre" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['tipo'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['tipo']; ?></div>
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