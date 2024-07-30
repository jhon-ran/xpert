//Validar # telefono de staff
function validateTelefonoStaff() {
    const inputField = document.getElementById('telefono');
    const errorMessage = document.getElementById('errorTelefono');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    //regex que valida si el input solo tiene datos alfanumericos
    const regex = /[0-9]/; 

    if (value === '') {
        errorMessage.textContent = 'El teléfono es obligatorio';
        errorMessage.style.display = 'inline';
    } else if (value.length != 10) {
        errorMessage.textContent = 'Debe tener 10 digitos';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (!regex.test(value)) {
        errorMessage.textContent = 'Solo puede contener digitos';
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