<?php
//Importar conexión a BD
include("../../bd.php");
//se inicializa variable de sesión
session_start();

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){
        //Si esta variable existe, se asigna ese valor, de lo contrario se queda
        $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
        //Se prepara sentencia para editar dato seleccionado (id)
        $sentencia = $conexion->prepare("SELECT * FROM redes_tour WHERE id=:id");
        //Asignar los valores que vienen del método GET (id seleccionado por params)
        $sentencia->bindParam(":id",$txtID);
        //Se ejecuta la sentencia con el valor asignado para borrar
        $sentencia->execute();
        //Popular el formulario con los valores de 1 registro
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);
        //Asignar los valores que vienen del formulario (POST)
        $link = $registro["link"];
        $id_tour = $registro["id_tour"];
        $id_logo = $registro["id_logo"];
}
//******Termina código para recibir registro******


//Verifciar si hay datos por el metodo post
if($_SERVER["REQUEST_METHOD"]=="POST"){
    include("../../conexion.php");
    //Array para guardar los errores
    $errores= array();
    //Variable para saber si se ha registrado correctamente
    $succes = false;

    //Validación: que exista la información enviada, lo vamos a igualar a ese valor,
    $txtID = (isset($_POST['txtID']))?$_POST['txtID']:"";

    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $link =(isset($_POST["link"]))? $_POST["link"]:null;
    $id_tour =(isset($_POST["id_tour"]))? $_POST["id_tour"]:null;
    $id_logo =(isset($_POST["id_logo"]))? $_POST["id_logo"]:null;

    //Si los datos están, se llena el array con los mensajes de errores
    //vqlidar aue link no está vacio
    if (empty($link)){
         $errores['link']= "Campo obligatorio";
    }

    if (empty($id_tour)){
         $errores['id_tour']= "Debe seleccionar un tour";
    }
  
    if (empty($id_logo)){
         $errores['id_logo']= "Debe seleccionar una red social";
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
            $sentencia = $conexion->prepare("UPDATE redes_tour SET link=:link, id_tour=:id_tour, id_logo=:id_logo WHERE id=:id");
            //Asignar los valores que vienen del formulario (POST)
            //Se convierte la palabra a minusculas antes de enviarlo a la BD con strtolower()
            $sentencia->bindParam(":id",$txtID);
            $sentencia->bindParam(":link",$link);
            $sentencia->bindParam(":id_tour",$id_tour);
            $sentencia->bindParam(":id_logo",$id_logo);

            print_r($id_tipo_staff);
            //Se ejecuta la sentencia con los valores de param asignados
            $sentencia->execute();
    
            //Mensaje de confirmación de creación que activa Sweet Alert 2
            //Llama a código de templates/header.php
            $mensaje="Asignación de red modificada";
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

//query para obtener los nombres del tipo de la tabla tours
$sentencia = $conexion->prepare("SELECT * FROM tours");
$sentencia->execute();
//se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
$tours = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//query para obtener los nombres del tipo de la tabla logos
$sentencia = $conexion->prepare("SELECT * FROM logos");
$sentencia->execute();
//se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
$lista_logos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<!--Nuevo look inicia-->
<header class="text-center my-4">
        <h1>Editar asignación de red social</h1>
</header>

<div class="row my-2">
    <div class="col-md-4"><br><br></div>
        <div class="col-md-4">
        <div class="card">
        <div class="card-header">Asignación</div>
        <div class="card-body">
            <!--Inicio envio de mensaje de error-->

            <!--Fin envio de mensaje de error-->
            <form action="editar.php" id="EditarRed" method="post">
                <div class="mb-3">
                    <label for="id" class="form-label">ID</label>
                    <input type="text" class="form-control" value ="<?php echo $txtID;?>" readonly name="txtID" id="txtID" aria-describedby="helpId" placeholder=""/>
                </div>
                <div class="mb-3">
                    <label for="link" class="form-label">Link de publicación</label>
                    <input type="text" class="form-control" name="link" id="link" oninput="validateAsignar()" aria-describedby="helpId" placeholder="Ingrese link de publicación" value="<?php echo isset($link) ? $link : ''; ?>" required/>
                    <!--Se llama mensaje de error de validacion de ../../js/validarNombreUsuario.js -->
                    <span id="errorlink" class="error"></span>
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['link'])): ?>
                            <div class="alert alert-danger mt-1"><?php echo $errores['link']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="id_tour" class="form-label">Tour</label>
                    <select class="form-select form-select-sm" name="id_tour" id="id_tour" onclick="validateAsignar()" required>
                        <option value="" selected>Seleccione una opción</option>
                            <?php foreach($tours as $tour){ ?>
                                    <option <?php echo ($id_tour == $tour['id'])?"selected":"";?> value="<?php echo $tour['id']; ?>"><?php echo $tour['titulo']; ?></option>
                            <?php }?>
                    </select>
                    <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                    <span id="errorTour" class="error"></span>   
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['id_tour'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['id_tour']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <div class="mb-3">
                    <label for="id_logo" class="form-label">Red social</label>
                    <select class="form-select form-select-sm" name="id_logo" id="id_logo" onclick="validateAsignar()" required>
                        <option value="" selected>Seleccione una opción</option>
                            <?php foreach($lista_logos as $logo){ ?>
                                    <option <?php echo ($id_logo == $logo['id'])?"selected":"";?> value="<?php echo $logo['id']; ?>"><?php echo $logo['nombre']; ?></option>
                            <?php }?>
                    </select>
                    <!--Se llama mensaje de error de validacion de ../../js/validartipoUsuario.js -->
                    <span id="errorLogo" class="error"></span>   
                    <!--Inicio envio de mensaje de error-->
                    <?php if (isset($errores['id_logo'])): ?>
                        <div class="alert alert-danger mt-1"><?php echo $errores['id_logo']; ?></div>
                    <?php endif; ?>
                    <!--Fin envio de mensaje de error-->
                </div>
                <button type="submit" id="submitBtn" class="btn btn-success">Editar</button>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </form>
            </div>
        </div>
    </div>
</div>
<!--Nuevo look termina-->
<br>
<br>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>

<!-- Llama funcion para validar redes con tours-->
<script src="../../js/validarAsignarRedes.js"> </script>