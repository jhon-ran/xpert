//Validaciones para asignar usuarios a cupones
function validateAsignarCupon() {
    const inputFieldUsuario = document.getElementById('id_usuario');
    const inputFieldCupon = document.getElementById('id_cupon');

    const errorMessageUsuario = document.getElementById('errorUsuario');
    const errorMessageCupon = document.getElementById('errorCupon');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const valueUsuario = inputFieldUsuario.value.trim();
    const valueCupon = inputFieldCupon.value.trim();

    if (valueUsuario === '') {
        errorMessageUsuario.textContent = 'Debes seleccionar un vendedor';
        errorMessageUsuario.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueCupon === '') {
        errorMessageCupon.textContent = 'Debes seleccionar un cupón';
        errorMessageCupon.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else {
        //si todo el input es vílido
        errorMessageUsuario.style.display = 'none';
        errorMessageCupon.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}
