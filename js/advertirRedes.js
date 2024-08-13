
    document.addEventListener('DOMContentLoaded', function() {
        // Find the label element
        var label = document.querySelector('label[for="redes"]');
        
        // Create a new warning message element
        var warningMessage = document.createElement('div');
        warningMessage.className = 'warning-message';
        warningMessage.textContent = 'Para asignar o modificar, ir a Redes sociales/Administrar';

        // Insert the warning message after the label
        label.parentNode.insertBefore(warningMessage, label.nextSibling);
    });

