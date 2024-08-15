<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
//si no existe la variable de sesión usuario_id, se redirige al index
if($_SESSION["usuario_tipo"]=="cliente" || $_SESSION["usuario_tipo"]=="ventas"){
    header('Location:../../index.php');
    exit();
}

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
        //Si esta variable existe, se asigna ese valor, de lo contrario se queda
        $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
        //Se prepara sentencia para editar dato seleccionado (id)
        $sentencia = $conexion->prepare("SELECT * FROM ubicaciones WHERE id=:id");
        //Asignar los valores que vienen del método GET (id seleccionado por params)
        $sentencia->bindParam(":id",$txtID);
        //Se ejecuta la sentencia con el valor asignado para borrar
        $sentencia->execute();
        //Popular el formulario con los valores de 1 registro
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);
        //Asignar los valores que vienen del formulario (POST)
        $geo = $registro["geo"];
        $estado = $registro["estado"];
        $poblacion = $registro["poblacion"];
        $direccion = $registro["direccion"];
}
//******Termina código para recibir registro******


if($_POST){
        //Array para guardar los errores
        $errores= array();
         //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        $txtID = (isset($_POST['txtID']))?$_POST['txtID']:"";
        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        $geo = (isset($_POST["geo"])? $_POST["geo"]:"");
        $estado = (isset($_POST["estado"])? $_POST["estado"]:"");
        $poblacion = (isset($_POST["poblacion"])? $_POST["poblacion"]:"");
        $direccion = (isset($_POST["direccion"])? $_POST["direccion"]:"");

        //Validaciones de campos
        if (strlen($geo) > 200) {
                $errores['geo'] = "No puede tener más de 200 caracteres";
        }
        if (empty($estado)){
            $errores['estado']= "Campo obligatorio";
        }
        if (strlen($estado) < 6) {
            $errores['estado'] = "No puede tener menos de 6 caracteres";
        }
        if (strlen($estado) > 20) {
            $errores['estado'] = "No puede tener más de 20 caracteres";
        }
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ \s\'\-]+$/", $estado)) {
        $errores['estado'] = "Solo puede contener letras y espacios";
        }
        if (empty($poblacion)){
                $errores['poblacion']= "Campo obligatorio";
        }
        if (strlen($poblacion) < 5) {
                $errores['poblacion'] = "Debe tener al menos 5 caracteres";
        }
        if (strlen($poblacion) > 15) {
                $errores['poblacion'] = "No puede tener más de 15 caracteres";
        }
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9\s\'\-]+$/", $poblacion)) {
        $errores['poblacion'] = "Solo puede contener letras y números";
        }


        //******Inicia validación tipo de las placas existente en bd*****
        /*
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $placas = $_POST['placas'];
                $id = $_POST['txtID'];
                // Consulta para ver si nombre tipo de staff ya existe en la base de datos
                $sql = "SELECT * FROM vehiculo WHERE placas = :placas AND id != :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':placas', $placas);
                 $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si el resultado es verdadero, el nombre ya existe y se muestra un mensaje de error
                if ($resultado) {
                        $errores['placas'] = "Ya existen esas placas";
                }
                
                } catch(PDOException $e) {
                echo "Error de conexión: ". $e->getMessage();
                }
        
                //******Termina validación de nombre de placas existente en bd*****
            */

        //Imprimir los errores
        foreach($errores as $error){
                $error;
           }

        //Si no hay errores (array de errores vacio)
        if(empty($errores)){

                try{
                //Preparar la inseción de los datos enviados por POST
                $sentencia = $conexion->prepare("UPDATE ubicaciones SET geo=:geo, 
                estado=:estado, poblacion=:poblacion, direccion=:direccion WHERE id=:id");

                //Asignar los valores que vienen del formulario (POST)
                $sentencia->bindParam(":id",$txtID);
                $sentencia->bindParam(":geo",$geo);
                $sentencia->bindParam(":estado",$estado);
                $sentencia->bindParam(":poblacion",$poblacion);
                $sentencia->bindParam(":direccion",$direccion);
                //Se ejecuta la sentencia con los valores de param asignados
                $sentencia->execute();
                //Mensaje de confirmación de creado que activa Sweet Alert 2
                $mensaje="Ubicación modificada";
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
            <h1>Editar ubicacion</h1>
</header>

<div class="row my-2">
        <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
                <div class="card">
                <div class="card-header">Ubicacion</div>
                        <div class="card-body">
                                <form action="editar.php" id="crearVehiculo" method="post">
                                        <div class="mb-3">
                                            <label for="id" class="form-label">ID</label>
                                            <input type="text" class="form-control" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
                                        </div>
                                        <div class="mb-3">
                                                <label for="geo" class="form-label">Geolocalización</label>
                                                <input type="text" class="form-control" value ="<?php echo $geo;?>" name="geo" id="geo" oninput="validateModelo()" aria-describedby="helpId" placeholder="Ingrese link" value="<?php echo isset($geo) ? $geo : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorGeo" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['geo'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['geo']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="estado" class="form-label">Año</label>
                                                <input type="text" class="form-control" value ="<?php echo $estado;?>" name="estado" id="estado" oninput="validateAnio()" aria-describedby="helpId" placeholder="Ingrese Estado" value="<?php echo isset($estado) ? $estado : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorEstado" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['estado'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['estado']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="poblacion" class="form-label">Población/ciudad</label>
                                                <input type="text" class="form-control" value ="<?php echo $poblacion;?>" name="poblacion" id="poblacion" oninput="validatePlacas()" aria-describedby="helpId" placeholder="Ingrese población/ciudad" value="<?php echo isset($poblacion) ? $poblacion : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorPoblacion" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['poblacion'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['poblacion']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="direccion" class="form-label">Dirección</label>
                                                <input type="text" class="form-control" value ="<?php echo $direccion;?>" name="direccion" id="direccion" oninput="validatePlacas()" aria-describedby="helpId" placeholder="Ingrese dirección" value="<?php echo isset($direccion) ? $direccion : ''; ?>" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorDireccion" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['direccion'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['direccion']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>

                                        <!--Botón de crear inicia inhabilitado para validaciones-->
                                        <button type="submit" id="submitBtn" class="btn btn-success">Editar</button>
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