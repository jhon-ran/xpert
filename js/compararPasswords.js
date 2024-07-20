//Script para verificar que las contraseñas sean iguales antes de mandar los datos por POST
function comparaPasswords() {
    const password = document.getElementById('password').value;
    const confirmarPassword = document.getElementById('confirmarPassword').value;
    const errorMessage = document.getElementById('errorCompararPasswords');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');
 
    // Verificar que el password y la confirmación sean iguales
    if (password.value!== confirmarPassword.value) {
        // Mostrar el error
        errorMessage.textContent = 'La contraseña y confirmación no coinciden';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else {
        //si todo el input es vílido
        errorMessage.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }
}
