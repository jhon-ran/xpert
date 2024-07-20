//Validar confiracion de password de usuario
function validateConfirmarPassword() {
    const inputField = document.getElementById('confirmarPassword');
    const errorMessage = document.getElementById('errorConfirmarPassword');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();

    if (value === '') {
        errorMessage.textContent = 'La confirmación es obligatoria';
        errorMessage.style.display = 'inline';
        submitBtn.disabled = true;
    } else {
        //si todo el input es vílido
        errorMessage.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}