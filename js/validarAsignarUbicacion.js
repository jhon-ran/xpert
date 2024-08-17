//Validaciones para asignar ubicación a un tour
function validateAsignarUbicacion() {
    const inputFieldUbicacion = document.getElementById('ubicacion');

    const errorMessageAsignar = document.getElementById('errorAsignar');
   
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const valueTour = inputFieldUbicacion.value.trim();

   if (valueTour === '') {
        errorMessageAsignar.textContent = 'Debes seleccionar un tour';
        errorMessageAsignar.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else {
        //si todo el input es válido
        errorMessageAsignar.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}