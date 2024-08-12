<?php 
include("../../bd.php"); 
//se inicializa variable de sesión
session_start();

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
        //Si esta variable existe, se asigna ese valor, de lo contrario se queda
        $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
        //Se prepara sentencia para editar dato seleccionado (id)
        $sentencia = $conexion->prepare("SELECT * FROM tipo_staff WHERE id=:id");
        //Asignar los valores que vienen del método GET (id seleccionado por params)
        $sentencia->bindParam(":id",$txtID);
        //Se ejecuta la sentencia con el valor asignado para borrar
        $sentencia->execute();
        //Popular el formulario con los valores de 1 registro
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);
        //Asignar los valores que vienen del formulario (POST)
        $tipo = $registro["tipo"];
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
        $tipo = (isset($_POST["tipo"])? $_POST["tipo"]:"");

        //Validar que el tipo de staff no esté vacio
        if (empty($tipo)){
                $errores['tipo']= "El tipo de staff es obligatorio";
        }
        //Validar si el tipo de staff no tiene menos de 4 caracteres
        if (strlen($tipo) < 4) {
                $errores['tipo'] = "El tipo de staff debe ser de al menos 4 caracteres";
        }
        //Validar si el el tipo de staff tiene más de 10 caracteres
        if (strlen($tipo) > 15) {
                $errores['tipo'] = "El tipo de staff no puede tener más de 15 caracteres";
        }
        //Validar que el tipo de staff solo tenga letras y num
        if (!preg_match("/^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9\s\'\-]+$/", $tipo)) {
        $errores['tipo'] = "Solo puede contener letras y números";
        }
      
 
        //******Inicia validación del tipo de staff existente en bd*****\
        try {
                $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $tipo = $_POST['tipo'];
                $id = $_POST['txtID'];
                // Consulta para ver si nombre del tipo de staff ya existe en la base de datos y que es diferente id que el propio (que llega en $_GET)
                $sql = "SELECT * FROM tipo_staff WHERE tipo = :tipo AND id != :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':tipo', $tipo);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si el resultado es verdadero, el tipo de staff ya existe y se muestra un mensaje de error
                if ($resultado) {
                        $errores['tipo'] = "Ya existe ese tipo";
                }

                } catch(PDOException $e) {
                echo "Error de conexión: ". $e->getMessage();
                }
        //******Termina validación el tipo de staff existente en bd*****\

        //Imprimir los errores
        foreach($errores as $error){
                $error;
                }


        //Si no hay errores (array de errores vacio)
        if(empty($errores)){
                try{
                        //Preparar modificar el registro enviados por POST
                        $sentencia = $conexion->prepare("UPDATE tipo_staff SET tipo=:tipo WHERE id=:id");
                        //Asignar los valores que vienen del formulario (POST)
                        $sentencia->bindParam(":id",$txtID);
                        //Se convierte el tipo a minusculas antes de enviarlo a la BD con strtoupper()
                        $sentencia->bindParam(":tipo",strtolower($tipo));
                        //Se ejecuta la sentencia con los valores de param asignados
                        $sentencia->execute();
                        //Mensaje de confirmación de modificación que activa Sweet Alert 2
                        $mensaje="Tipo modificado";
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

<!--Nuevo look empieza-->
<header class="text-center">
            <h1>Editar tipo</h1>
</header>
<div class="row my-2">
<div class="col-md-4"><br><br></div>
<div class="col-md-4">
        <div class="card">
                <div class="card-header">Tipo de staff</div>
                        <div class="card-body">
                                <form action="editar.php" id="editarTipo" method="post">
                                        <div class="mb-3">
                                                <label for="nombre" class="form-label">ID</label>
                                                <input type="text" class="form-control" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
                                        </div>
                                        <div class="mb-3">
                                                <label for="tipo" class="form-label">Tipo</label>
                                                <input type="text" class="form-control" value = "<?php echo $tipo;?>" name="tipo" id="tipo" oninput="validateTipoStaff()" aria-describedby="helpId" placeholder="" required/>
                                                <!--Se llama mensaje de error de validacion de ../../js/validarNombre.js -->
                                                <span id="errorTipo" class="error"></span>
                                                <!--Inicio envio de mensaje de error-->
                                                <?php if (isset($errores['tipo'])): ?>
                                                        <div class="alert alert-danger mt-1"><?php echo $errores['tipo']; ?></div>
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
<script src="../../js/validarTipoStaff.js"> </script>
