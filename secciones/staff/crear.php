<?php
//Importar conexión a BD
include("../../bd.php");
//se inicializa variable de sesión
session_start();
//Verifciar si hay datos por el metodo post
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include("../../conexion.php");
    //Array para guardar los errores
    $errores= array();
    //Variable para saber si se ha registrado correctamente
    $succes = false;
    //Imprimir los datos del formulario en pantalla
    //print_r($_POST);

    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $nombre =(isset($_POST["nombre"]))? $_POST["nombre"]:null;
    $apellidos =(isset($_POST["apellidos"]))? $_POST["apellidos"]:null;
    $telefono =(isset($_POST["telefono"]))? $_POST["telefono"]:null;
    $id_tipo_staff  =(isset($_POST["id_tipo_staff "]))? $_POST["id_tipo_staff "]:null;

    //Si los datos están, se llena el array con los mensajes de errores
    if (empty($nombre)){
         $errores['nombre']= "El nombre es obligatorio";
    }
    //Validar si el nombre tiene más de 25 caracteres
    if (strlen($nombre) > 25) {
        $errores['nombre'] = "El nombre no puede tener más de 25 caracteres";
    }
    //Validar si el nombre tiene menos de 2 caracteres
    if (strlen($nombre) < 2) {
        $errores['nombre'] = "El nombre no puede tener menos de 2 caracteres";
    }
    //Validar si el nombre solo contener letras, espacios, guiones y apóstrofes
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\'\-]+$/', $nombre)) {
        $errores['nombre'] = "El nombre solo puede contener letras, espacios, guiones y apóstrofes";
    }
    if (empty($apellidos)){
        $errores['apellidos']= "Los apellidos son obligatorios";
   } 
    //Validar si los apellidos tienen más de 40 caracteres
    if (strlen($apellidos) > 40) {
    $errores['apellidos'] = "Los apellidos no pueden tener más de 40 caracteres";
    }
    //Validar si los apellidos tienen menos de 2 caracteres
    if (strlen($apellidos) < 2) {
        $errores['apellidos'] = "Los apellidos no pueden tener menos de 2 caracteres";
    }
    //Validar si apellidos solo contener letras, espacios, guiones y apóstrofes
    if (!preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\'\-]+$/', $apellidos)) {
        $errores['apellidos'] = "Los apellidos solo pueden contener letras, espacios, guiones y apóstrofes";
    }

        //******Inicia validación si id_tipo_staff existente en bd**
        /*
        try {
            $conn = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Consulta para ver si usuario ya existe en la base de datos
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
        
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            // Si el resultado es verdadero, el usuario ya existe y se muestra un mensaje de error
            if ($resultado) {
                $errores['email'] = "Ya existe un usuario con ese correo";
            }
        
        } catch(PDOException $e) {
            echo "Error de conexión: ". $e->getMessage();
        }
        */
        //******Termina validación de email existente en bd*****

    //Imprimir errores en pantalla si los hay
   foreach($errores as $error){
        $error;
   }

   //Si no hay errores (array de errores vacio)
   if(empty($errores)){
        //Conexion a la base de datos
        try{
            //Preparar la inseción de los datos enviados por POST
            $sentencia = $conexion->prepare("INSERT INTO staff(id,nombre,apellidos,telefono,id_tipo_staff) 
            VALUES (null, :nombre, :apellidos, :telefono, :id_tipo_staff)");

            //Se convierte la palabra a minusculas antes de enviarlo a la BD con strtolower()
            //se asigna el valor a variantes para evitar error 
            //$ayapaneco_min = strtolower($ayapaneco);
            //$variante_min = strtolower($variante);
            //$significado_min = strtolower($significado);
            
            //Asignar los valores que vienen del formulario (POST)
            //Se convierte la palabra a minusculas antes de enviarlo a la BD con strtolower()
            $sentencia->bindParam(":nombre",$nombre);
            $sentencia->bindParam(":apellidos",$apellidos);
            $sentencia->bindParam(":telefono",$telefono);
            $sentencia->bindParam(":id_tipo_staff",$id_tipo_staff);

            //print_r($id_categoria);
            //Se ejecuta la sentencia con los valores de param asignados
            $sentencia->execute();
    
            //Mensaje de confirmación de creación que activa Sweet Alert 2
            //Llama a código de templates/header.php
            $mensaje="Staff creado";
            //Redirecionar después de crear a la lista de usuarios
            header("Location:index.php?mensaje=".$mensaje);
            
    
        }catch(Exception $ex){
            echo "Error de conexión:".$ex->getMessage();
        }
   }else {
    //La variable para mensaje de exito se actualiza a false si no se pudo insertar
    $succes=false;
   }

}

        //query para obtener los nombres del tipo de la tabla tipo_staff
        $sentencia = $conexion->prepare("SELECT * FROM tipo_staff");
        $sentencia->execute();
        //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
        $tipos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    <!--Nuevo look inicia-->
    <header class="text-center">
            <h1>Registrar staff</h1>
    </header>
    

<div class="row">
    <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Datos de staff</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="crear.php" id="registroStaff" method="post">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" oninput="validateNombreUsuario()" aria-describedby="helpId" placeholder="Ingrese nombre(s)" value="<?php echo isset($nombre) ? $nombre : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarNombreUsuario.js -->
                    <span id="errorNombreUsuario" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['nombre'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['nombre']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="apellidos" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" name="apellidos" id="apellidos" oninput="validateApellidosUsuario()" aria-describedby="helpId" placeholder="Ingrese apellido(s)" value="<?php echo isset($apellidos) ? $apellidos : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarNombreUsuario.js -->
                    <span id="errorApellidosUsuario" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['apellidos'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['apellidos']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" oninput="validateEmail()" aria-describedby="helpId" placeholder="Ingrese teléfono" value="<?php echo isset($telefono) ? $telefono : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarEmail.js -->
                    <span id="errorEmail" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['email'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['email']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="id_tipo_staff" class="form-label">Tipo de staff</label>
                    <select class="form-select form-select-sm" name="id_tipo_staff" id="id_tipo_staff" onclick="validateTipoUsuario()" required>
                        <option value="" selected>Seleccione una opción</option>
                            <?php foreach($tipos as $tipo){ ?>
                                    <option value="<?php echo $tipo['id']?>"><?php echo $tipo["tipo"]?></option>
                            <?php }?>
                    </select>
                    <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                    <span id="errorTipo" class="error"></span>   
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['tipo'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['tipo']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <button type="submit" id="submitBtn" class="btn btn-success">Registrar</button>
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