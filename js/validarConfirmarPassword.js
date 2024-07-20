//Validar confiracion de password de usuario
function validateConfirmarPassword() {
    //input de campo password
    const password = document.getElementById('password');
    //input de campo confirmar password
    const confirmarPassword = document.getElementById('confirmarPassword');
    //error de confirmar password
    const errorMessage = document.getElementById('errorConfirmarPassword');
    //error de comparación de inputs
    const errorMessageComparar = document.getElementById('errorComparar');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = confirmarPassword.value.trim();
    const value2 = password.value.trim();
    if (value === '') {
        errorMessage.textContent = 'La confirmación es obligatoria';
        errorMessage.style.display = 'inline';
        submitBtn.disabled = true;
    } else if (value2 !== value) {
        // Mostrar el error
        errorMessageComparar.textContent = 'La contraseña y confirmación no coinciden';
        errorMessageComparar.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else {
        //si todo el input es vílido
        errorMessage.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}