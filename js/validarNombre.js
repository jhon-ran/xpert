
//Validar que nombre de cupon
function validateNombre() {
    const inputField = document.getElementById('nombre');
    const errorMessage = document.getElementById('errorNombre');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    //regex que valida si el input solo tiene datos alfanumericos
    const regex = /^[a-zA-Z0-9]+$/; 

    if (value === '') {
        errorMessage.textContent = 'Campo obligatorio';
        errorMessage.style.display = 'inline';
    } else if (value.length < 4) {
        errorMessage.textContent = 'Debe tener al menos 4 caracteres';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value.length > 10) {
        errorMessage.textContent = 'No puede tener más de 10 caracteres';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (!regex.test(value)) {
        errorMessage.textContent = 'Solo puede contener letras y números';
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
