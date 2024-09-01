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
        $conductor = (isset($_POST["conductor"])? $_POST["conductor"]:"");

        //Validar que el modelo no esté vacio
        if (empty($modelo)){
                $errores['modelo']= "El modelo es obligatorio";
        }
        //Validar si el modelo no tiene menos de 4 caracteres
        if (strlen($modelo) < 5) {
                $errores['modelo'] = "Debe tener al menos 5 caracteres";
        }
        //Validar si el modelo tiene más de 10 caracteres
        if (strlen($modelo) > 20) {
                $errores['modelo'] = "No puede tener más de 20 caracteres";
        }
        //Validar que el modelo solo tenga letras y num
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9\s\'\-]+$/", $modelo)) {
        $errores['modelo'] = "Solo puede contener letras y números";
        }

        //Validar si el año está vacío
        if (empty($anio)){
            $errores['anio']= "El año es obligatorio";
        }
        //Validar si el año no es un número negativo
        if ($anio < 0) {
            $errores['anio'] = "El año no puede ser negativo";
        }
        //Validar que no sea menor de 2014
        if ($anio < 2014) {
                $errores['anio'] = "No puede ser menor a 2014";
        }
        //Va
        //Validar que la duración no sea mayor a 8hrs
        if ($anio > 2024) {
            $errores['anio'] = "No puede ser mayor a 2024";
        }
     

        //Validar que las placas no esté vacio
        if (empty($placas)){
                $errores['placas']= "Las placas son obligatorias";
        }
        //Validar si las placas no tiene menos de 4 caracteres
        if (strlen($placas) < 5) {
                $errores['placas'] = "Debe tener al menos 5 caracteres";
        }
        //Validar si las placas tiene más de 10 caracteres
        if (strlen($placas) > 10) {
                $errores['placas'] = "No puede tener más de 10 caracteres";
        }
        //Validar que el las placas solo tenga letras y num
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9\s\'\-]+$/", $placas)) {
        $errores['placas'] = "Solo puede contener letras y números";
        }

        //******Inicia validación tipo de las placas existente en bd*****
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
                $sentencia = $conexion->prepare("INSERT INTO vehiculo(id,modelo,anio,placas,conductor) 
                VALUES (null,:modelo,:anio,:placas,:conductor)" );
                
                //Asignar los valores que vienen del formulario (POST)
                //Se convierte el tipo a mayusculas antes de enviarlo a la BD con strtolower()
                
                $sentencia->bindParam(":modelo",$modelo);
                $sentencia->bindParam(":anio",$anio);
                $sentencia->bindParam(":placas",strtoupper($placas));

                //Si campo vacío, se conviertr a Null para evitar error: Integrity constraint violation: 1452
                if($conductor==''){
                        $conductor = null;
                        $sentencia->bindParam(":conductor",$conductor);
                }

                $sentencia->bindParam(":conductor",$conductor);
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
        //query para obtener los miembros de staff que son conductores. Explicación:
        /*SELECT s.*: Selecciona todas las columnas de la tabla staff. Se utiliza el alias s para abreviar.
        FROM staff s: Especifica la tabla staff como la tabla principal y le da el alias s.
        JOIN tipo_staff ts ON s.id_tipo_staff = ts.id: Une la tabla tipo_staff con la tabla staff utilizando la relación de clave foránea. Se usa el alias ts para tipo_staff.
        WHERE ts.tipo = 'conductor': Filtra los resultados para incluir solo aquellos registros donde el tipo en tipo_staff es "conductor".
        */
        $sentencia = $conexion->prepare("SELECT s.*FROM staff s JOIN tipo_staff ts ON s.id_tipo_staff = ts.id WHERE ts.tipo = 'conductor';");

        $sentencia->execute();
        //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
        $conductores = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<!--Nuevo look empieza-->
<header class="text-center my-4">
            <h1>Alta de vehículo</h1>
</header>

<div class="row my-2">
        <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
                <div class="card">
                <div class="card-header">Vehículo</div>
                        <div class="card-body">
                                <form action="crear.php" id="crearVehiculo" method="post">
                                        <div class="mb-3">
                                                <label for="modelo" class="form-label">Modelo</label>
                                                <input type="text" class="form-control" name="modelo" id="modelo" oninput="validateModelo()" aria-describedby="helpId" placeholder="Ingrese modelo" value="<?php echo isset($tipo) ? $tipo : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorModelo" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['modelo'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['modelo']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="anio" class="form-label">Año</label>
                                                <input type="number" class="form-control" name="anio" id="anio" oninput="validateAnio()" aria-describedby="helpId" placeholder="Ingrese año" value="<?php echo isset($tipo) ? $tipo : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorAnio" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['anio'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['anio']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="placas" class="form-label">Placas</label>
                                                <input type="text" class="form-control" name="placas" id="placas" oninput="validatePlacas()" aria-describedby="helpId" placeholder="Ingrese placas" value="<?php echo isset($tipo) ? $tipo : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorPlacas" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['placas'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['placas']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="conductor" class="form-label">Conductor</label>
                                                <select class="form-select form-select-sm" name="conductor" id="conductor" onclick="validateTipoStaff()">
                                                        <option value="" selected>Seleccione una opción</option>
                                                        <?php foreach($conductores as $conductor){ ?>
                                                                <option value="<?php echo $conductor['id']?>"><?php echo $conductor["nombre"], ' ', $conductor["apellidos"]?></option>
                                                        <?php }?>
                                                </select>
                                                <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                                                <span id="errorIdTipoStaff" class="error"></span>   
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['id_tipo_staff'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['id_tipo_staff']; ?></div>
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

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>
<!-- Validadciones JS -->
<script src="../../js/validarModeloVehiculo.js"> </script>
<script src="../../js/validarAnioVehiculo.js"> </script>
<script src="../../js/validarPlacasVehiculo.js"> </script>