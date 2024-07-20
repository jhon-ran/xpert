//Validar correo de usuario
function validateEmail() {
    const inputField = document.getElementById('email');
    const errorMessage = document.getElementById('errorEmail');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();

    if (value === '') {
        errorMessage.textContent = 'El correo es obligatorio';
        errorMessage.style.display = 'inline';
    } else if (!/^\S+@\S+\.\S+$/.test(value)) {
        errorMessage.textContent = 'El formato del correo no es válido';
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