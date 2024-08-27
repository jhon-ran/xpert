<?php 
include("../../bd.php"); 
//se inicializa variable de sesión
//se inicializa variable de sesión
session_start();
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<body>
<div class="container mt-5">
    <h1 class="mb-4">Reportes del Sistema</h1>
    <!-- Botones para cargar reportes -->
    <div class="mb-4 row">
        <div class="col-12 col-sm-6 col-md-4 mb-2">
            <button class="btn btn-primary w-100" onclick="cargarReporte('usuarios')">Reporte de Usuarios</button>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-2">
            <button class="btn btn-primary w-100" onclick="cargarReporte('cupones')">Reporte de Cupones</button>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-2">
            <button class="btn btn-primary w-100" onclick="cargarReporte('tours')">Reporte de Tours</button>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-2">
            <button class="btn btn-primary w-100" onclick="cargarReporte('cupones_usuarios')">Cupones Asignados a Usuarios</button>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-2">
            <button class="btn btn-primary w-100" onclick="cargarReporte('cupones_tours')">Cupones Asignados a Tours</button>
        </div>
        <div class="col-12 col-sm-6 col-md-4 mb-2">
            <button class="btn btn-success w-100" onclick="exportarExcel()">Exportar</button>
        </div>
    </div>

    <!-- Contenedor donde se mostra el reporte -->
    <div id="contenedor-reporte">
        <p>Seleccione un reporte para mostrar los datos.</p>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cargarReporte(tipo) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'cargar_reporte.php?tipo=' + tipo, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('contenedor-reporte').innerHTML = xhr.responseText;
                            document.getElementById('contenedor-reporte').innerHTML = xhr.responseText;
                    //Inicia código para llamar a Data Tables y aplicarlo a las tablas que se cargan        
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
                        //Termina código para llamar a Data Tables y aplicarlo a las tablas que se cargan   
                } else {
                    document.getElementById('contenedor-reporte').innerHTML = '<p>Error al cargar el reporte.</p>';
                }
            };
            xhr.send();
        }

        function exportarExcel() {
            var reporte = document.getElementById('contenedor-reporte').innerHTML;
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'exportar_excel.php';
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'reporte';
            input.value = reporte;
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>