//Validar tipo de usuario
function validateTipoUsuario() {
    const inputField = document.getElementById('tipo');
    const errorMessage = document.getElementById('errorTipo');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    if (value === '') {
        errorMessage.textContent = 'El tipo de usuario es obligatorio';
        errorMessage.style.display = 'inline';
    } else {
        //si todo el input es vílido
        errorMessage.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}