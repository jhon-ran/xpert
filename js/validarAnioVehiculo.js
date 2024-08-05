//validar el año del vehiculo
function validateAnio() {
    const inputField = document.getElementById('anio');
    const errorMessage = document.getElementById('errorAnio');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();

    if (value === '') {
        errorMessage.textContent = 'El año es obligatorio';
        errorMessage.style.display = 'inline';
        submitBtn.disabled = true;
    } else if (value <= 0) {
        errorMessage.textContent = 'No puede ser negativo';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value < 2014) {
        errorMessage.textContent = 'No puede ser menor a 2014';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value > 2024) {
        errorMessage.textContent = 'No puede ser mayor a 2024';
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