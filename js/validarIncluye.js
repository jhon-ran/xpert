//validar campo incluye de tour: campo 10
function validateIncluye() {
    const inputField = document.getElementById('incluye');
    const errorMessage = document.getElementById('errorIncluye');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const value = inputField.value.trim();
    //regex que valida si el input no tiene los caracteres prohibidos
    const forbiddenChars = /[=<>|]/; 

  if (value.length > 1100) {
        errorMessage.textContent = 'No puede tener más de 1100 caracteres';
        errorMessage.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (forbiddenChars.test(value)) {
        errorMessage.textContent = 'No puede contener = - | < >';
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