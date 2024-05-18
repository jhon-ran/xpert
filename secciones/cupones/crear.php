<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
if($_POST){
        //Array para guardar los errores
        $errores= array();

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
                $errores['nombre'] = "El nombre del cupón debe tener al menos 4 caracteres";
        }
        //Validar si el nombre de cupón tiene más de 10 caracteres
        if (strlen($nombre) > 10) {
                $errores['nombre'] = "El nombre del cupón no puede tener más de 10 caracteres";
        }
        //Validar que nombre de cupón solo tenga letras y num
        if (!preg_match("/^[a-zA-Z0-9]*$/", $nombre)) {
        $errores['nombre'] = "El nombre del cupón solo puede contener letras y números";
        }

        //******Inicia validación de nombre de cupón existente en bd*****
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $email = $_POST['nombre'];
                // Consulta para ver si nombre de cupón ya existe en la base de datos
                $sql = "SELECT * FROM cupones WHERE nombre = :nombre";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si el resultado es verdadero, el nombre ya existe y se muestra un mensaje de error
                if ($resultado) {
                        $errores['nombre'] = "Ya existe un cupón con ese nombre";
                }
                
                } catch(PDOException $e) {
                echo "Error de conexión: ". $e->getMessage();
                }
        
                //******Termina validación de nombre de cupón existente en bd*****



        //Validar que descuento no esté vacio
        if (empty($descuento)){
                $errores['descuento']= "El monto del descuento es obligatorio";
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
        //Validar que inicio de validez no esté vacio
        if (empty($terminoValidez)){
                $errores['terminoValidez']= "El termino de validez es obligatorio";
        }
        //Validar que termino de validez no es igual o menor que inicio de validez
        if ($terminoValidez <= $inicioValidez) {
                $errores['terminoValidez'] = "El termino de validez no puede ser igual o menor que el inicio de validez.";
        }
        //Validar que restricciones solo contengan letras, números, espacios, guiones y apóstrofes
        /*
        if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\'\-]+$/', $restricciones)) {
                $errores['restricciones'] = "Las restricciones solo pueden tener letras, espacios, guiones y apóstrofes.";
        }
        */
        //Validar si las restricciones no tienen más de 50 caracteres
        if (strlen($restricciones) > 50) {
                $errores['restricciones'] = "Las restricciones no pueden tener más de 50 caracteres";
        }
        //Validar que las restricciones no contengan = - | < >
        if (preg_match('/[=<>|]/', $restricciones)) {
                $errores['restricciones'] = "Las restricciones no pueden contener los caracteres especiales = - | < >";
        }
        //Imprimir los errores
        foreach($errores as $error){
                $error;
           }

        //Si no hay errores (array de errores vacio)
        if(empty($errores)){

                try{
                //Preparar la inseción de los datos enviados por POST
                $sentencia = $conexion->prepare("INSERT INTO cupones(id,nombre,descuento,inicioValidez,terminoValidez,restricciones) 
                VALUES (null, :nombre, :descuento, :inicioValidez, :terminoValidez, :restricciones)" );
                
                //Asignar los valores que vienen del formulario (POST)
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
                //Mensaje de confirmación de creado que activa Sweet Alert 2
                $mensaje="Registro creado";
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
            <h1>Crear cupón</h1>
</header>
<br>
<div class="card mx-auto" style="width:40%;">
        <div class="card-header">Datos del cupón</div>
                <div class="card-body">
                        <form action="crear.php" id="crearCupones" method="post">
                                <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Ingrese nombre de cupón" required/>
                                        <!--Inicio envio de mensaje de error-->
                                        <?php if (isset($errores['nombre'])): ?>
                                                <div class="alert alert-danger mt-1"><?php echo $errores['nombre']; ?></div>
                                        <?php endif; ?>
                                        <!--Fin envio de mensaje de error-->
                                </div>
                                <div class="mb-3">
                                        <label for="descuento" class="form-label">Descuento</label>
                                        <input type="number" class="form-control" name="descuento" id="descuento" aria-describedby="helpId" placeholder="Ingrese monto de descuento" required/>
                                        <!--Inicio envio de mensaje de error-->
                                        <?php if (isset($errores['descuento'])): ?>
                                                <div class="alert alert-danger mt-1"><?php echo $errores['descuento']; ?></div>
                                        <?php endif; ?>
                                        <!--Fin envio de mensaje de error-->
                                </div>
                                <div class="mb-3">
                                        <label for="inicioValidez" class="form-label">Inicio de validez</label>
                                        <input type="datetime-local" class="form-control" name="inicioValidez" id="inicioValidez"aria-describedby="helpId" placeholder="" required/>
                                        <!--Inicio envio de mensaje de error-->
                                        <?php if (isset($errores['inicioValidez'])): ?>
                                                <div class="alert alert-danger mt-1"><?php echo $errores['inicioValidez']; ?></div>
                                        <?php endif; ?>
                                        <!--Fin envio de mensaje de error-->
                                </div>
                                <div class="mb-3">
                                        <label for="terminoValidez" class="form-label">Termino de validez</label>
                                        <input type="datetime-local" class="form-control" name="terminoValidez" id="terminoValidez"aria-describedby="helpId" placeholder="" required/>
                                        <!--Inicio envio de mensaje de error-->
                                        <?php if (isset($errores['terminoValidez'])): ?>
                                                <div class="alert alert-danger mt-1"><?php echo $errores['terminoValidez']; ?></div>
                                        <?php endif; ?>
                                        <!--Fin envio de mensaje de error-->
                                </div>
                                <div class="mb-3">
                                        <label for="restricciones">Restricciones</label>
                                        <textarea class="form-control" name="restricciones" id="restricciones" rows="3"></textarea>
                                        <!--Inicio envio de mensaje de error-->
                                        <?php if (isset($errores['restricciones'])): ?>
                                                <div class="alert alert-danger mt-1"><?php echo $errores['restricciones']; ?></div>
                                        <?php endif; ?>
                                        <!--Fin envio de mensaje de error-->
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