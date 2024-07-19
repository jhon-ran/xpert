//validar capacidad de tour
function validateCapacidad() {
    const inputField = document.getElementById('capacidad');
    const errorMessage = document.getElementById('errorCapacidad');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();

    if (value === '') {
        errorMessage.textContent = 'La capacidad es obligatoria';
        errorMessage.style.display = 'inline';
        submitBtn.disabled = true;
    } else if (value <= 0) {
        errorMessage.textContent = 'Debe ser mayor a cero.';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value > 50) {
        errorMessage.textContent = 'No puede no puede ser más de 50';
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