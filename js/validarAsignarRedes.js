//Validaciones para asignar redes sociales a tours
function validateAsignar() {
    const inputFieldLink = document.getElementById('link');
    const inputFieldTour = document.getElementById('id_tour');
    const inputFieldLogo = document.getElementById('id_logo');

    const errorMessageLink = document.getElementById('errorlink');
    const errorMessageTour = document.getElementById('errorTour');
    const errorMessageLogo = document.getElementById('errorLogo');
    //para habilitar y deshabilitar el botón de crear
    const submitBtn = document.getElementById('submitBtn');

    //el input se limplia y se guarda en vairable
    const valueLink = inputFieldLink.value.trim();
    const valueTour = inputFieldTour.value.trim();
    const valueLogo = inputFieldLogo.value.trim();


    if (valueLink === '') {
        errorMessageLink.textContent = 'Campo obligatorio';
        errorMessageLink.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueLink.length > 100) {
        errorMessageLink.textContent = 'No puede tener más de 100 caracteres';
        errorMessageLink.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueTour === '') {
        errorMessageTour.textContent = 'Debes seleccionar un tour';
        errorMessageTour.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else if (valueLogo === '') {
        errorMessageLogo.textContent = 'Debes seleccionar una red social';
        errorMessageLogo.style.display = 'inline';
        // se dishabilita el botón
        submitBtn.disabled = true;
    } else {
        //si todo el input es vílido
        errorMessageLink.style.display = 'none';
        errorMessageTour.style.display = 'none';
        errorMessageLogo.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

}
