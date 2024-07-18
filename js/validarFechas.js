function validateFechas() {
    const errorMessageInicio = document.getElementById('errorInicio');
    const errorMessageTermino = document.getElementById('errorTermino');
    const inicioValidez = document.getElementById('inicioValidez').value;
    const terminoValidez = document.getElementById('terminoValidez').value;
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    // Parse the date strings to Date objects
    const inicioDate = new Date(inicioValidez);
    const terminoDate = new Date(terminoValidez);

      // Validate the date condition
         // Check if either field is empty
    if (!inicioValidez) {
        errorMessageInicio.textContent = 'Inicio obligatorio';
        errorMessageInicio.style.display = 'inline';
        submitBtn.disabled = true;
    }  else if (!terminoValidez) {
        errorMessageTermino.textContent = 'Término obligatorio';
        errorMessageTermino.style.display = 'inline';
        submitBtn.disabled = true;
    } else if (terminoDate <= inicioDate) {
        errorMessageTermino.textContent = 'El termino no puede ser igual o menor que el inicio';
        errorMessageTermino.style.display = 'inline';
        submitBtn.disabled = true;
        
    } else {
        //si todo el input es válido
        errorMessageInicio.style.display = 'none';
        errorMessageTermino.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}
