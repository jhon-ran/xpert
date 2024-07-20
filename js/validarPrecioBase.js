//validar campo precio base de tour: campo 19
function validatePrecioBase() {
    const inputField = document.getElementById('precioBase');
    const errorMessage = document.getElementById('errorPrecioBase');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();

    if (value === '') {
        errorMessage.textContent = 'Es obligatorio';
        errorMessage.style.display = 'inline';
        submitBtn.disabled = true;
    } else if (value <= 0) {
        errorMessage.textContent = 'Debe ser mayor a cero';
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