//Validar apellidos de usuario
function validateApellidosUsuario() {
    const inputField = document.getElementById('apellidos');
    const errorMessage = document.getElementById('errorApellidosUsuario');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    //regex que valida si el input solo tiene datos alfanumericos
    const regex = /^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\'\-]+$/; 

    if (value === '') {
        errorMessage.textContent = 'Apellidos son obligatorios';
        errorMessage.style.display = 'inline';
    } else if (value.length < 2) {
        errorMessage.textContent = 'Debe tener al menos 2 caracteres';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value.length > 40) {
        errorMessage.textContent = 'No puede tener más de 40 caracteres';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (!regex.test(value)) {
        errorMessage.textContent = 'Solo puede contener letras, espacios, guiones y apóstrofes';
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