//Validar password de usuario
function validatePassword() {
    const inputField = document.getElementById('password');
    const errorMessage = document.getElementById('errorPassword');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    //regex que valida formato de password
    const regex = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&])[A-Za-z0-9@$!%*?&]{8,}$/; 

    if (value === '') {
        errorMessage.textContent = 'La contraseña es obligatoria';
        errorMessage.style.display = 'inline';
    } else if (!regex.test(value)) {
        errorMessage.textContent = 'Debe tener al menos 8 caracteres, una mayúscula, un número y un carácter especial';
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