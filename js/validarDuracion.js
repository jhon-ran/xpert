//validar duración de tour: campo 2
function validateDuracion() {
    const inputField = document.getElementById('duracion');
    const errorMessage = document.getElementById('errorDuracion');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();

    if (value === '') {
        errorMessage.textContent = 'La duración es obligatoria';
        errorMessage.style.display = 'inline';
        submitBtn.disabled = true;
    } else if (value <= 0) {
        errorMessage.textContent = 'Debe ser mayor a cero.';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value > 14) {
        errorMessage.textContent = 'No puede ser mayor a 14hrs';
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