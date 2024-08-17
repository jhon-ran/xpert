//Validaciones para asignar redes sociales a tours
function validateUbicaciones() {
    const inputFieldGeo = document.getElementById('geo');
    const inputFieldEstado = document.getElementById('estado');
    const inputFieldPoblacion = document.getElementById('poblacion');
    const inputFieldDireccion = document.getElementById('direccion');

    const errorMessageGeo = document.getElementById('errorGeo');
    const errorMessageEstado = document.getElementById('errorEstado');
    const errorMessagePoblacion = document.getElementById('errorPoblacion');
    const errorMessageDireccion = document.getElementById('errorDireccion');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const valueGeo = inputFieldGeo.value.trim();
    const valueEstado = inputFieldEstado.value.trim();
    const valuePoblacion = inputFieldPoblacion.value.trim();
    const valueDireccion = inputFieldDireccion.value.trim();

    //regex que valida si el input solo tiene letras y espacios
    const regex = /^[a-zA-ZáéíóúñÁÉÍÓÚÑ \s\'\-]+$/; 
    //regex que valida si el input solo tiene datos alfanumericos y espacios
    const regexPoblacion = /^[a-zA-ZáéíóúñÁÉÍÓÚÑ0-9 \s\'\-]+$/; 

    if (valueGeo.length > 200) {
        errorMessageGeo.textContent = 'No puede tener más de 200 caracteres';
        errorMessageGeo.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueEstado === '') {
        errorMessageEstado.textContent = 'Campo obligatorio';
        errorMessageEstado.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueEstado.length < 6) {
        errorMessageEstado.textContent = 'Debe tener al menos 6 caracteres';
        errorMessageEstado.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueEstado.length > 20) {
        errorMessageEstado.textContent = 'No puede tener más de 20 caracteres';
        errorMessageEstado.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (!regex.test(valueEstado)) {
        errorMessageEstado.textContent = 'Solo puede contener letras y espacios';
        errorMessageEstado.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valuePoblacion === '') {
        errorMessagePoblacion.textContent = 'Campo obligatorio';
        errorMessagePoblacion.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valuePoblacion.length < 5) {
        errorMessagePoblacion.textContent = 'Debe tener al menos 5 caracteres';
        errorMessagePoblacion.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valuePoblacion.length > 15) {
        errorMessagePoblacion.textContent = 'No puede tener más de 15 caracteres';
        errorMessagePoblacion.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (!regexPoblacion.test(valuePoblacion)) {
        errorMessagePoblacion.textContent = 'Solo puede contener letras y números';
        errorMessagePoblacion.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueDireccion.length > 60) {
        errorMessageDireccion.textContent = 'No puede tener más de 60 caracteres';
        errorMessageDireccion.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    }else {
        //si todo el input es vílido
        errorMessageGeo.style.display = 'none';
        errorMessageEstado.style.display = 'none';
        errorMessagePoblacion.style.display = 'none';
        errorMessageDireccion.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}
