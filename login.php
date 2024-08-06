<?php 
//se inicializa variable de sesión
session_start();
//Verifciar si hay datos por el metodo post
if ($_SERVER["REQUEST_METHOD"]=="POST") {
    include("conexion.php");
    //Array para guardar los errores
    $errores= array();

    //print_r($_POST);
    //Guardar los datos en variables
    //isset() -> verifica si existe una variable, se guardan los datos en variables. Sino se deja en null
    $email=(isset($_POST['email']))?htmlspecialchars($_POST['email']):null;
    $password=(isset($_POST['password']))?$_POST['password']:null;

    //*********INICIA CODIGO PARA VALIDAR CAPTCHA ANTES DE ENVIAR DATOS DE FORMULARIO
    //Obtener la IP del usuario
    $ip = $_SERVER['REMOTE_ADDR'];
    //variable para almacenar la respuesta del captcha
    $captcha = $_POST['g-recaptcha-response'];
    $secretKey = "";
    //Validar captcha
    $respuesta =file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
    //Decodificar la respuesta en un array
    $atributos =json_decode($respuesta, TRUE);
    //Si el captcha no es correcto
    if(!$atributos['success']){
        $errores['captcha'] = "Verificar captcha";
    }
    //*********TERMINA CODIGO PARA VALIDAR CAPTCHA ANTES DE ENVIAR DATOS DE FORMULARIO

//
    //Validación que el correo no esté vacío y que tenga el formato requerido
   if (empty($email)) {
        $errores['email'] = "El correo es obligatorio";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        //Verificar que el correo sea válido (formato) 
        $errores['email'] = "El correo no es válido";
    } 
    //validar que la contraseña no está vacía
    if (empty($password)) {
        $errores['password'] = "La contraseña es obligatoria";
    }

    //Si no hay errores (array de errores vacio)
    if(empty($errores)){
        //Verificar si el usuario (email) existe en BD
    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$baseDatos",$usuario,$contrasena);
        //para que PDO maneje los errores de manera automática
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Consultar la base de datos
        $sql ="SELECT * FROM usuarios WHERE email=:email";
        //Preparar la consulta
        $sentencia = $pdo->prepare($sql);
        //Ejecutar la consulta
        $sentencia->execute(['email'=>$email]);
        //Obtener los datos de la consulta
        $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        //print_r($usuarios);

        //Se inicializa la variable en flase para verificar si el password es correcto
        $login = false; 
        //Recorrer el arreglo de usuarios recuperados de la consulta
        foreach($usuarios as $usuario) {
            //Comparar password encriptado ingresado con el de la BD
            if (password_verify($password, $usuario['password'])) {
                //Guardar el id y nombre del usuario de la BD en las variables de sesión
                $_SESSION['usuario_id']= $usuario['id'];
                //para guardar nombre y appellidos en una sola variable de session
                $_SESSION['usuario_nombre']= $usuario['nombre']." ".$usuario['apellidos'];
                $_SESSION['usuario_tipo']= $usuario['tipo'];
                $_SESSION['nombre']= $usuario['nombre'];
                $_SESSION['apellidos']= $usuario['apellidos'];

                $login = true;
            }
        }

        if($login){
            echo "Existe en la BD";
            //redirección al index
            header("Location: index.php");
        }else{
            $error ="El usuario o contraseña son incorrectos";  
        }
         
    } catch (PDOException $ex) {
        echo "Error de conexión:".$ex->getMessage();
    }

    }else{
        //Imprimir errores en pantalla si los hay
        foreach($errores as $error){
            //echo "<li>$error</li>";
            $error;
        }
        //Redireccionar a login
        //echo  "<a href='login.php'>Regresar a login</>";
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
         <!-- estilo para personalizar -->
        <link rel="stylesheet" href="style.css">
        <!-- cdn para el captcha-->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <!-- cdn para Sweet Alert 2, alertas de acciones -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Login</title>
    </head>
    <body>

        <header class="text-center">
            <h1>Tours Xpert</h1>
        </header>
        <br>
        <main class="container">
                <!--Inicia código de mensaje de alerta cuando se borra o crea registro-->
                <!--Si hay algo en el métod get-->
                <?php if(isset($_GET['mensaje'])){ ?>
                    <!--se corre el mensaje de eliminado en línea 19-->
                    <script>
                        Swal.fire({icon:"success", title:"<?php echo $_GET['mensaje'];?>"});
                    </script>
                <?php } ?>
                <!--Termina código de mensaje de alerta cuando se borra registro-->

            <div class="row">
                <div class="col-md-4">
                <br><br>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <!--Inicio envio de mensaje de error-->
                        <div class="card-body">
                        <!--Inicio envio de mensaje de error-->
                        <?php if(isset($error)) { ?>
                            <?php foreach($errores as $error){ ?>
                            <div class="alert alert-danger" role="alert">
                                <strong><?php echo $error;?></strong>
                            </div>
                            <?php }?>
                        <?php }?>
                        <!--Fin envio de mensaje de error-->
                            <form action="login.php" method="post">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo</label>
                                    <input type="email" class="form-control" name="email" id="email" oninput="validateEmail()" aria-describedby="helpId" placeholder="Ingrese correo"/>
                                    <!--Se llama mensaje de error de validacion de ../../js/validarEmail.js -->
                                    <span id="errorEmail" class="error"></span>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ingrese contraseña"/>
                                    <!--se llama a función para que el usuario pueda ver la contraseña que escribió-->
                                    <input type="checkbox" onclick="mostrarPassword()"> <small>Mostrar</small>
                                </div>
                                <div class="g-recaptcha" data-sitekey="6LeYc74pAAAAAAvREuXjkM4inSyqJPTb5xxTr3Gk"></div>
                                <button type="submit" class="btn btn-primary" id="submitBtn">Iniciar sesión</button>
                                <a name="" id="" class="btn btn-success" href="registro.php" role="button" >Registrarse</a>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>



        </main>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <footer class="bg-body-tertiary text-center text-lg-start" class="">
            <!-- Copyright -->
            <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2024 Copyright:
                <a class="text-body" href="https://mvptest.me/xpert/">Tours Xpert</a>
            </div>
            <!-- Copyright -->
        </footer>
      <!-- Bootstrap JavaScript Libraries -->
      <script
          src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
          integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
          crossorigin="anonymous"
      ></script>
      
      <script
          src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
          integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
          crossorigin="anonymous"
      ></script>

<!-- Validaciones para campos de input de carpeta js-->
<script src="js/validarEmail.js"> </script>
<script src="js/validarPassword.js"> </script>

      <!--función para mostrar password-->
    <script>
        function mostrarPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        }
    </script>
      </body>
</html>