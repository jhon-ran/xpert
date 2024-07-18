//Validar que nombre de tour
function validateTitulo() {
    const inputField = document.getElementById('titulo');
    const errorMessage = document.getElementById('errorTitulo');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    //regex que valida si el input no tiene los caracteres prohibidos
    const forbiddenChars = /[=<>|]/; 

    if (value === '') {
        errorMessage.textContent = 'El título es obligatorio';
        errorMessage.style.display = 'inline';
    } else if (value.length < 5) {
        errorMessage.textContent = 'Debe tener al menos 5 caracteres';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (value.length > 60) {
        errorMessage.textContent = 'No puede tener más de 60 caracteres';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (forbiddenChars.test(value)) {
        errorMessage.textContent = 'No puede contener no pueden contener = - | < >';
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