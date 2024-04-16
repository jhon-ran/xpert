<?php 
//Se valida si no hay variable de sesión y si no la hay
if(session_status()==PHP_SESSION_NONE) {
    //Se inicia la variable de sesión
    session_start();
}
//Se destruye variable de sesión
session_destroy();
//Se redirecciona a la página de login
header('Location:login.html');
exit();
?>