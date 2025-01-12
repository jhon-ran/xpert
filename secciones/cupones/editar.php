<?php 
include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
//se guarda el valor de la variable de usuario logeado para usarla como modificador en tabla cupones_historial
$usuario_id = $_SESSION['usuario_id'];

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
        //Si esta variable existe, se asigna ese valor, de lo contrario se queda
        $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
        //Se prepara sentencia para editar dato seleccionado (id)
        $sentencia = $conexion->prepare("SELECT * FROM cupones WHERE id=:id");
        //Asignar los valores que vienen del método GET (id seleccionado por params)
        $sentencia->bindParam(":id",$txtID);
        //Se ejecuta la sentencia con el valor asignado para borrar
        $sentencia->execute();
        //Popular el formulario con los valores de 1 registro
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);
        //Asignar los valores que vienen del formulario (POST)
        $nombre = $registro["nombre"];
        $descuento = $registro["descuento"];
        $inicioValidez = $registro["inicioValidez"];
        $terminoValidez = $registro["terminoValidez"];
        $restricciones = $registro["restricciones"];
        //print_r($restricciones);
}
//******Termina código para recibir registro******

//******Inicia código para modificar registro******
if($_POST){
        //Array para guardar los errores
        $errores= array();
        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        $txtID = (isset($_POST['txtID']))?$_POST['txtID']:"";
        //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
        //de lo contratrio lo deja en blanco
        $nombre = (isset($_POST["nombre"])? $_POST["nombre"]:"");
        $descuento = (isset($_POST["descuento"])? $_POST["descuento"]:"");
        $inicioValidez = (isset($_POST["inicioValidez"])? $_POST["inicioValidez"]:"");
        $terminoValidez = (isset($_POST["terminoValidez"])? $_POST["terminoValidez"]:"");
        $restricciones = (isset($_POST["restricciones"])? $_POST["restricciones"]:"");

        //Validar que nombre de cupón no esté vacio
        if (empty($nombre)){
                $errores['nombre']= "El nombre del cupón es obligatorio";
        }
        //Validar si el nombre de cupón no tiene menos de 4 caracteres
        if (strlen($nombre) < 4) {
                $errores['nombre'] = "El nombre del cupón debe ser de al menos 4 caracteres";
        }
        //Validar si el nombre de cupón tiene más de 10 caracteres
        if (strlen($nombre) > 10) {
                $errores['nombre'] = "El nombre del cupón no puede tener más de 10 caracteres";
        }
        //Validar que nombre de cupón solo tenga letras y num
        if (!preg_match("/^[a-zA-Z0-9]*$/", $nombre)) {
        $errores['nombre'] = "El nombre del cupón solo puede contener letras y números";
        }
      
 
        //******Inicia validación de nombre de cupón existente en bd*****\
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $nombre = $_POST['nombre'];
                $id = $_POST['txtID'];
                // Consulta para ver si nombre de cupón ya existe en la base de datos y que es diferente id que el propio (que llega en $_GET)
                $sql = "SELECT * FROM cupones WHERE nombre = :nombre AND id != :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si el resultado es verdadero, el nombre ya existe y se muestra un mensaje de error
                if ($resultado) {
                        $errores['nombre'] = "Ya existe otro cupón con ese nombre";
                }

                } catch(PDOException $e) {
                echo "Error de conexión: ". $e->getMessage();
                }
        //******Termina validación de nombre de cupón existente en bd*****\

        //Validar que descuento no esté vacio
        if (empty($descuento)){
                $errores['descuento']= "El monto del descuento es obligatorio";
        }
        //Validar que el monto del descuento es positivo
        if ($descuento < 0){
                $errores['descuento']= "El descuento debe ser mayor a cero";
        }
        //Validar que el monto del descuento no es mayor a 200
        if ($descuento > 200){
                $errores['descuento']= "El monto del descuento no puede ser mayor a $200";
        }
        //Validar que inicio de validez no esté vacio
        if (empty($inicioValidez)){
                $errores['inicioValidez']= "El inicio de validez es obligatorio";
        }
        //Validar que inicio de validez no es anterior a la fecha actual
        if (strtotime($inicioValidez) < time()) {
                $errores['inicioValidez'] = "El inicio de validez debe ser posterior a la fecha actual";
        }
        //Validar que inicio de cupón no sea + de 1 año en el futuro
        $futureDate=date('Y-m-d', strtotime('+1 year'));
        if ($inicioValidez > $futureDate) {
                $errores['inicioValidez'] = "El inicio de validez no puede ser más de un año desde la fecha actual";
        }
        //Validar que termino de validez no esté vacio
        if (empty($terminoValidez)){
                $errores['terminoValidez']= "El termino de validez es obligatorio";
        }
        //Validar que termino de validez no es igual o menor que inicio de validez
        if ($terminoValidez <= $inicioValidez) {
                $errores['terminoValidez'] = "El termino de validez no puede ser igual o menor que el inicio de validez.";
        }
        //Validar que restricciones solo contengan letras, números, espacios, guiones y apóstrofes
        /*if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\'\-]+$/', $restricciones)) {
                $errores['restricciones'] = "Las restricciones solo pueden tener letras, espacios, guiones y apóstrofes.";
        }*/
        //Validar si el nombre de cupón tiene más de 10 caracteres
        if (strlen($restricciones) > 50) {
                $errores['restricciones'] = "Las restricciones no pueden tener más de 50 caracteres";
        }
        if (preg_match('/[=<>|]/', $restricciones)) {
                $errores['restricciones'] = "Las restricciones no pueden contener los caracteres especiales = - | < >";
        }
        //Imprimir los errores
        foreach($errores as $error){
                $error;
                }


        //Si no hay errores (array de errores vacio)
        if(empty($errores)){

                 /*INICIA CODIGO PARA VINCULAR EL USUARIO LOGGEADO PARA QUE
                APAREZCA COMO MODIFICADOR CUANDO EN LA TABLA cupones_registro*/
                try {
                        // Conexión a la base de datos
                        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Preparar la sentencia para establecer la variable de sesión en MySQL
                        $stmt = $conexion->prepare("SET @modificador = :usuario_id");

                        // Vincular el parámetro :usuario_id a la variable $usuario_id
                        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

                        // Ejecutar la sentencia
                        $stmt->execute();

                        echo "Variable de sesión @modificador establecida correctamente.";
                } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                }
                /*TERMINA CODIGO PARA VINCULAR EL USUARIO LOGGEADO PARA QUE
                APAREZCA COMO MODIFICADOR CUANDO EN LA TABLA cupones_registro*/

                try{
                        //Preparar modificar el registro enviados por POST
                        $sentencia = $conexion->prepare("UPDATE cupones SET nombre=:nombre, 
                        descuento=:descuento, inicioValidez=:inicioValidez, terminoValidez=:terminoValidez, 
                        restricciones=:restricciones WHERE id=:id");
                        //Asignar los valores que vienen del formulario (POST)
                        $sentencia->bindParam(":id",$txtID);
                        //Se convierte el nombre del cupón a mayusculas antes de enviarlo a la BD con strtoupper()
                        $sentencia->bindParam(":nombre",strtoupper($nombre));
                        $sentencia->bindParam(":descuento",$descuento);
                        $sentencia->bindParam(":inicioValidez",$inicioValidez);
                        $sentencia->bindParam(":terminoValidez",$terminoValidez);
                        if($restricciones == null){
                                //Si el campo de restricciones está vacío se le asigna un valor por defecto
                                $restricciones = "Ninguna";
                                $sentencia->bindParam(":restricciones",$restricciones);
                        }
                        $sentencia->bindParam(":restricciones",$restricciones);
                        //Se ejecuta la sentencia con los valores de param asignados
                        $sentencia->execute();
                        //Mensaje de confirmación de modificación que activa Sweet Alert 2
                        $mensaje="Cupón modificado";
                        //Redirecionar después de modificar a la lista de cupones
                        header("Location:index.php?mensaje=".$mensaje);
                }catch(Exception $ex){
                        echo "Error de conexión:".$ex->getMessage();
                        }
                }else {
                //La variable para mensaje de exito se actualiza a false si no se pudo insertar
                $succes=false;
                }
    }
    //******Termina código para modificar registro******
?>


<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>
<script src="../../js/validarFechas.js"> </script>

<!--Nuevo look empieza-->
<header class="text-center my-2">
            <h1>Editar cupón</h1>
</header>
<div class="row my-2">
<div class="col-md-4"><br><br></div>
<div class="col-md-4">
        <div class="card">
                <div class="card-header">Datos del cupón</div>
                        <div class="card-body">
                                <form action="editar.php" id="editarCupones" method="post">
                                        <div class="mb-3">
                                                <label for="nombre" class="form-label">ID</label>
                                                <input type="text" class="form-control" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
                                        </div>
                                        <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" value = "<?php echo $nombre;?>" name="nombre" id="nombre" oninput="validateNombre()" aria-describedby="helpId" placeholder="" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorNombre" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['nombre'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['nombre']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="descuento" class="form-label">Descuento</label>
                                                <input type="number" class="form-control" value = "<?php echo $descuento;?>" name="descuento" id="descuento" oninput="validateDescuento()" aria-describedby="helpId" placeholder="Monto de descuento en dólares" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarDescuento.js -->
                                                <span id="errorDescuento" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['descuento'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['descuento']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="inicioValidez" class="form-label">Inicio de validez</label>
                                                <input type="datetime-local" class="form-control" name="inicioValidez" id="inicioValidez" value = "<?php echo $inicioValidez;?>" aria-describedby="helpId" placeholder="" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarFechas.js -->
                                            
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['inicioValidez'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['inicioValidez']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="terminoValidez" class="form-label">Termino de validez</label>
                                                <input type="datetime-local" class="form-control" name="terminoValidez" id="terminoValidez" oninput="validateFechas()" value ="<?php echo $terminoValidez;?>" aria-describedby="helpId" placeholder="" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarFechas.js -->
                                        
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['terminoValidez'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['terminoValidez']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <div class="mb-3">
                                                <label for="restricciones">Restricciones</label>
                                                <textarea class="form-control" name="restricciones" id="restricciones" oninput="validateRestricciones()" rows="3"><?php echo $restricciones;?></textarea>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarRestricciones.js -->
                                                <span id="errorRestricciones" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['restricciones'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['restricciones']; ?></div>
                                                <?php endif; ?>
                                                <!--Fin envio de mensaje de error-->
                                        </div>
                                        <button type="submit" id="submitBtn" class="btn btn-success">Editar</button>
                                        <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                                </form>
                        </div>
                <!--<div class="card-footer text-muted"></div>-->
                </div>
        </div>
</div>
    <!--Nuevo look termina-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>

<!-- Llama funcion para validar cupones-->
<script src="../../js/validarNombre.js"> </script>
<script src="../../js/validarDescuento.js"> </script>
<script src="../../js/validarRestricciones.js"> </script>
