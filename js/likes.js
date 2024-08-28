// Selecciona todos los botones de like
const likeButtons = document.querySelectorAll(".card__btn");

// Recorre cada botón
likeButtons.forEach((likeButton) => {
  likeButton.addEventListener("click", () => {
    // Encuentra el artículo (tarjeta) más cercano al botón clicado y obtén su atributo data-id-tour
    const idTour = likeButton.closest('article').dataset.idTour;

    // Verifica si idTour está definido
    if (!idTour) {
      console.error('idTour no está definido.');
      return;
    }

    // Envía una solicitud al servidor para registrar el like
    fetch('index.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `id_tour=${idTour}&id_usuario=${idUsuario}`,
    })
    .then(response => response.json())  // Cambiar a .json() después de confirmar que el backend envía JSON correcto
    .then(data => {
      console.log(data); // Ver la respuesta en la consola
      if (data.status === 'liked') {
        likeButton.classList.add("card__btn--like");
      } else {
        likeButton.classList.remove("card__btn--like");
      }
    })
    .catch(error => console.error('Error:', error));
  });
});


