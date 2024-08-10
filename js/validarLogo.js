// Para validar el formato de logo
function validateLogo(inputElement) {

    const errorMessage = document.getElementById('errorFoto');
    const submitBtn = document.getElementById('submitBtn');
    // Se obtiene el input
    const fileInput = inputElement.files[0];

    if (fileInput) {
        // Se obtiene la extensión del archivo a adjuntar
        const fileExtension = fileInput.name.split('.').pop().toLowerCase();

        // Se definen las extensiones de archivo per;itidos
        const acceptedExtensions = ['jpeg', 'jpg', 'png'];

        // Se valida si el archivo a adjuntar es de esas extensiones
        if (!acceptedExtensions.includes(fileExtension)) {
            // Se muestra mensqje de error
            errorMessage.textContent = 'El logo solo puede ser un archivo JPEG o PNG';
            errorMessage.style.display = 'inline';
            // se dishabilita el botón
            submitBtn.disabled = true;
            
            // Se limpia el varlo para evitar envion
            inputElement.value = '';
        } else {
        //si todo el input es válido
        errorMessage.style.display = 'none';
        // se habilita el botón
        submitBtn.disabled = false;
    }

    }
}

// Attach the function to the file input change event
document.getElementById('foto').addEventListener('change', function() {
    validateLogo(this);
});
