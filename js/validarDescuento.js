
function validateDescuento() {
    const inputField = document.getElementById('descuento');
    const errorMessage = document.getElementById('errorDescuento');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    //regex que valida si el input solo tiene datos numéricos
    const regex = /^[0-9]+$/; 

    if (value === '') {
        errorMessage.textContent = 'El monto es obligatorio';
        errorMessage.style.display = 'inline';
    } else if (value <= 0) {
        errorMessage.textContent = 'Debe ser mayor a cero.';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value > 200) {
        errorMessage.textContent = 'No puede no puede ser mayor a $200';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (!regex.test(value)) {
        errorMessage.textContent = 'Solo puede contener números';
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