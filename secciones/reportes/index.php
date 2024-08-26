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
        <div class="mb-4">
            <button class="btn btn-primary" onclick="cargarReporte('usuarios')">Reporte de Usuarios</button>
            <button class="btn btn-primary" onclick="cargarReporte('cupones')">Reporte de Cupones</button>
            <button class="btn btn-primary" onclick="cargarReporte('tours')">Reporte de Tours</button>
            <button class="btn btn-primary" onclick="cargarReporte('cupones_usuarios')">Cupones Asignados a Usuarios</button>
            <button class="btn btn-primary" onclick="cargarReporte('cupones_tours')">Cupones Asignados a Tours</button>
            <button class="btn btn-success" onclick="exportarExcel()">Exportar</button>
        </div>

        <!-- Contenedor donde se mostrará el reporte -->
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