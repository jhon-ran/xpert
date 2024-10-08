
</main>
<footer class="footer-custom text-center text-lg-start">
  <!-- Copyright -->
  <div class="footer-content text-center p-3">
    © 2024 Copyright:
    <a class="footer-link" href="https://mvptest.me/xpert/">Tours Xpert</a>
  </div>
  <!-- Copyright -->
</footer>

<!-- Bootstrap JavaScript Libraries -->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
></script>

<!--Función para llamar Data Tables-->
<script>

  
  $(document).ready(function(){
    $("#tabla_id").DataTable({
      "pageLength":10,
       //para que sea responsivo
       //responsive: true,
       responsive: {
        breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet', width: 1024 },
            { name: 'fablet', width: 768 },
            { name: 'phone', width: 480 }
        ]
    },
      //para que columnas se reduzcan con pantalla chica
      autoWidth: false,
      lengthMenu:[
        [3,5,10,25,50],
        [3,5,10,25,50]
      ],
      "language": {
          //No carga modulo de lengua, genera error, descomentar cuando haya solución
            "url":"https://cdn.datatables.net/plug-ins/2.0.5/i18n/es-MX.json"
        }
    });
  }); 

</script>

<script>
  function borrar(id){
    //Se llama a la función del SweetAlert API para mostrar un mensaje de confirmación
    Swal.fire({
      title: "¿Estás seguro que quieres borrar el registro?",
      showCancelButton: true,
      confirmButtonText: "Sí"
    }).then((result) => {
        //Si el usuario confirma la eliminación, se redirecciona a la página de eliminación
        if (result.isConfirmed) {
          window.location="index.php?txtID="+id;
        } 
    });
        //index.php?txtID=
  }
</script>
</body>
</html>