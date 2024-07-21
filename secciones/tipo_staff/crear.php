<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
if($_POST){
        //Array para guardar los errores
        $errores= array();

        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");

        //Validar que nombre de cupón no esté vacio
        if (empty($tipo)){
                $errores['tipo']= "El tipo de staff es obligatorio";
        }
        //Validar si el nombre de cupón no tiene menos de 4 caracteres
        if (strlen($tipo) < 4) {
                $errores['tipo'] = "El tipo de staff debe tener al menos 4 caracteres";
        }
        //Validar si el nombre de cupón tiene más de 10 caracteres
        if (strlen($tipo) > 10) {
                $errores['tipo'] = "El tipo de staff no puede tener más de 10 caracteres";
        }
        //Validar que nombre de cupón solo tenga letras y num
        if (!preg_match("/^[a-zA-Z0-9]*$/", $tipo)) {
        $errores['tipo'] = "El tipo solo puede contener letras y números";
        }

        //******Inicia validación de nombre de cupón existente en bd*****
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                //$email = $_POST['nombre'];
                // Consulta para ver si nombre de cupón ya existe en la base de datos
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


        //Imprimir los errores
        foreach($errores as $error){
                $error;
           }

        //Si no hay errores (array de errores vacio)
        if(empty($errores)){

                try{
                //Preparar la inseción de los datos enviados por POST
                $sentencia = $conexion->prepare("INSERT INTO tipo_staff(id,tipo) 
                VALUES (null, :tipo)" );
                
                //Asignar los valores que vienen del formulario (POST)
                //Se convierte el tipo a minusculas antes de enviarlo a la BD con strtolower()
                $sentencia->bindParam(":tipo",strtolower($tipo));
                //Se ejecuta la sentencia con los valores de param asignados
                $sentencia->execute();
                //Mensaje de confirmación de creado que activa Sweet Alert 2
                $mensaje="Tipo creado";
                //Redirecionar después de crear a la lista de cupones con link de Sweet Alert 2
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
            <h1>Añadir tipo</h1>
</header>

<div class="row">
        <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
                <div class="card">
                <div class="card-header">Nombre del tipo</div>
                        <div class="card-body">
                                <form action="crear.php" id="crearTipo" method="post">
                                        <div class="mb-3">
                                                <label for="nombre" class="form-label">Tipo</label>
                                                <input type="text" class="form-control" name="tipo" id="tipo" oninput="validateNombre()" aria-describedby="helpId" placeholder="Ingrese nombre de cupón" value="<?php echo isset($tipo) ? $tipo : ''; ?>" required/>
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