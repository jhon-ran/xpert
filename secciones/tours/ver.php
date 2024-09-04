<!-- Importar conexión a BD-->
<?php include("../../bd.php"); 

//se inicializa variable de sesión
session_start();
//se guarda el valor de la variable de usuario logeado para usarla como modificador en tabla tours_historial
$usuario_id = $_SESSION['usuario_id'];

//******Inicia código para recibir registro******
//Para verificar que se envía un id
if(isset($_GET['txtID'])){

    //Si esta variable existe, se asigna ese valor, de lo contrario se queda
    $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];
    //Se prepara sentencia para editar dato seleccionado (id)
    $sentencia = $conexion->prepare("SELECT * FROM tours WHERE id=:id");
    //Asignar los valores que vienen del método GET (id seleccionado por params)
    $sentencia->bindParam(":id",$txtID);
    //Se ejecuta la sentencia con el valor asignado para borrar
    $sentencia->execute();
    //Popular el formulario con los valores de 1 registro
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    //Asignar los valores que vienen del formulario (POST)
    $titulo = $registro["titulo"];
    $duracion = $registro["duracion"];
    $tipo = $registro["tipo"];
    $capacidad = $registro["capacidad"];
    $idiomas = $registro["idiomas"];
    //Recibir parámetro de la foto
    $foto = $registro["foto"];
    //Se continuan los bindParam después de foto
    $vistaGeneral = $registro["vistaGeneral"];
    $destacado = $registro["destacado"];
    $itinerario = $registro["itinerario"];
    $incluye = $registro["incluye"];
    $ubicacion = $registro["ubicacion"];
    $queTraer = $registro["queTraer"];
    $infoAdicional = $registro["infoAdicional"];
    $polCancel = $registro["polCancel"];
    $actividades = $registro["actividades"];
    $incluyeTransporte = $registro["incluyeTransporte"];
    $transporte = $registro["transporte"];
    $staff = $registro["staff"];
    $precioBase = $registro["precioBase"];
    $id_cupon = $registro["id_cupon"];
}
//******Termina código para recibir registro******


    //query para obtener los nombres del staff y excluir a los que sean de tipo conductor. Explicación:
    /*SELECT s.*: Selecciona todas las columnas de la tabla staff. Se utiliza el alias s para abreviar.
    FROM staff s: Especifica la tabla staff como la tabla principal y le da el alias s.
    JOIN tipo_staff ts ON s.id_tipo_staff = ts.id: Une la tabla tipo_staff con la tabla staff utilizando la relación de clave foránea. Se usa el alias ts para tipo_staff.
    WHERE ts.tipo != 'conductor': Filtra los resultados para excluir los registros donde el valor de la columna tipo en tipo_staff sea 'conductor'.
    */
    $sentencia = $conexion->prepare("SELECT s.*
    FROM staff s
    JOIN tipo_staff ts ON s.id_tipo_staff = ts.id
    WHERE ts.tipo != 'conductor';
    ");
    $sentencia->execute();
    $nombres = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //query para obtener los vehiculos registrados de tabla vehiculo
    $sentencia = $conexion->prepare("SELECT * FROM vehiculo");
    $sentencia->execute();
        //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
    $vehiculos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector

    //query para obtener los idiomas registrados de tabla idiomas
    $sentencia = $conexion->prepare("SELECT * FROM idiomas");
    $sentencia->execute();
    //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
    $lenguas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //******Termina código para actualizar registro******

    //query para obtener las redes del tour. Explicación:
    /*
SELECT redes_tour.id AS association_id, tours.id AS tour_id, tours.titulo AS tour_title, logos.nombre AS logo_name, logos.id AS logo_id: Selecciona columnas específicas de varias tablas. Las columnas seleccionadas son:

redes_tour.id, que se etiqueta como association_id en el resultado.
tours.id, que se etiqueta como tour_id.
tours.titulo, que se etiqueta como tour_title.
logos.nombre, que se etiqueta como logo_name.
logos.id, que se etiqueta como logo_id.
FROM redes_tour: Esta línea indica que la consulta comenzará seleccionando datos de la tabla redes_tour.

LEFT JOIN tours ON tours.id = redes_tour.id_tour: Realiza una unión izquierda (LEFT JOIN) entre la tabla redes_tour y la tabla tours. Todos los registros de redes_tour se incluirán en el resultado, y si hay coincidencias en la tabla tours según la condición tours.id = redes_tour.id_tour, esos datos se agregarán. Si no hay coincidencias, los campos de tours aparecerán como NULL.

LEFT JOIN logos ON logos.id = redes_tour.id_logo: Realiza otra unión izquierda (LEFT JOIN), esta vez entre la tabla redes_tour y la tabla logos, utilizando la condición logos.id = redes_tour.id_logo.

WHERE redes_tour.id = :id: Filtra los resultados para que solo se incluyan las filas donde el valor de redes_tour.id sea igual al parámetro :id. Este parámetro :id es el id del tour actual
    */
    $sentencia = $conexion->prepare("SELECT 
    redes_tour.id AS association_id,
    redes_tour.link AS url,
    tours.id AS tour_id,
    tours.titulo AS tour_title,
    logos.nombre AS logo_name,
    logos.foto AS pic,
    logos.id AS logo_id
FROM 
    redes_tour
LEFT JOIN 
    tours ON tours.id = redes_tour.id_tour
LEFT JOIN 
    logos ON logos.id = redes_tour.id_logo
WHERE 
    redes_tour.id_tour = :id;");
    $sentencia->bindParam(':id', $txtID);

    $sentencia->execute();
    //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
    $redes = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    //query para obtener la ubicación registrados de tabla ubicaciones
    $sentencia = $conexion->prepare("SELECT * FROM ubicaciones");
    $sentencia->execute();
    //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
    $lista_ubicaciones = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //query para los cupones existentes de lq tabla cupones
    $sentencia = $conexion->prepare("SELECT * FROM cupones");
    $sentencia->execute();
    //se guarda la setencia ejecutada en otra variable para llamarla con loop en selector
    $lista_cupones = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Se llama el header desde los templates-->
<?php include("../../templates/header.php"); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center my-4">
                <div><h1><?php echo $titulo;?></h1></div>
            </div>
             <hr class="mb-4">
            <!-- Distribución responsiva en una sola línea -->
            <div class="row text-center mb-4">
                <div class="col-md-3 col-sm-6 mb-2">
                    <h5 class="fw-bold"><i class="fa fa-clock-o text-primary" aria-hidden="true"></i> Duración</h5>
                    <div><?php echo $duracion; ?>hrs.
                    <!--Se evalua si incluye transporte y de ser el caso se muestra en la tarjeta -->
                        <?php if($registro['incluyeTransporte'] == "sí"){ ?>
                            <?php echo " + Transportación"?> 
                        <?php } ?></div>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <h5 class="fw-bold"><i class="fa fa-globe text-primary" aria-hidden="true"></i> Tipo de tour</h5>
                    <div><?php echo $tipo; ?></div>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <h5 class="fw-bold"><i class="fa fa-users text-primary" aria-hidden="true"></i> Tamaño de grupo</h5>
                    <div><?php echo $capacidad; ?></div>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <h5 class="fw-bold"><i class="fa fa-language text-primary" aria-hidden="true"></i> Idiomas</h5>
                    <div>
                        <?php
                        foreach ($lenguas as $idioma) {
                            if ($idiomas == $idioma['id']) {
                                echo $idioma['lengua'];
                                break;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Ajuste de tamaño de la imagen -->
            <div class="mb-4 pb-2 text-center">
                <img src="<?php echo $foto;?>" class="img-fluid rounded-top img-thumbnail" alt="Foto tour" style="max-width: 60%; height: auto;"/>
            </div>
            <div class="mb-4 border-bottom pb-2 text-center">
            <h5 class="fw-bold">Redes sociales</h5>
                <div>
                    <?php
                    foreach($redes as $red){
                        // Ruta base para recuperar los logos
                        $rutaBaseImagenes = '../logo_redes/';

                        // Construye la ruta completa a la imagen
                        $rutaImagen = $rutaBaseImagenes . $red['pic'];

                        echo '<a href="' . htmlspecialchars($red['url']) . '" target="_blank">';
                        echo '<img src="' . htmlspecialchars($rutaImagen) . '" alt="Logo de ' . htmlspecialchars($red['logo_name']) . '" style="width: 40px; height: 40px; margin: 5px;">';
                        echo '</a>';
                    }
                    ?>
                </div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Vista general</h5>
                <div><?php echo nl2br($vistaGeneral);?></div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Destacado</h5>
                <div><?php echo nl2br($destacado);?></div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Itinerario</h5>
                <div><?php echo nl2br($itinerario);?></div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Incluye</h5>
                <div><?php echo nl2br($incluye);?></div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Ubicación</h5>
                <div>
                    <?php
                        foreach($lista_ubicaciones as $geo){
                            if ($ubicacion == $geo['id']){
                                echo $geo['poblacion'];
                                break;
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Qué traer</h5>
                <div><?php echo nl2br($queTraer);?></div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Información adicional</h5>
                <div><?php echo nl2br($infoAdicional);?></div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Política de cancelación</h5>
                <div><?php echo nl2br($polCancel);?></div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Actividades para hacer</h5>
                <div><?php echo nl2br($actividades);?></div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-4 border-bottom pb-2">
                    <h5 class="fw-bold">Incluye transporte</h5>
                    <div><?php echo $incluyeTransporte; ?></div>
                </div>
                <div class="col-md-12 mb-4 border-bottom pb-2">
                    <h5 class="fw-bold">Vehículo</h5>
                    <div>
                        <?php
                        foreach ($vehiculos as $vehiculo) {
                            if ($transporte == $vehiculo['id']) {
                                echo $vehiculo['modelo'];
                                break;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="mb-4 border-bottom pb-2">
                <h5 class="fw-bold">Staff a cargo</h5>
                <div>
                    <?php
                        foreach($nombres as $nombre){
                            if ($staff == $nombre['id']){
                                echo $nombre["nombre"] . ' ' . $nombre["apellidos"];
                                break;
                            }
                        }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-4 border-bottom pb-2">
                    <h5 class="fw-bold">Precio desde</h5>
                    <div><?php echo $precioBase; ?></div>
                </div>
                <div class="col-md-12 mb-4 border-bottom pb-2">
                    <h5 class="fw-bold">Descuento</h5>
                    <div>
                        <?php
                        foreach ($lista_cupones as $cupon) {
                            if ($id_cupon == $cupon['id']) {
                                echo $cupon["nombre"];
                                break;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="text-left mt-4">
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Regresar</a>
            </div>
            <br>
        </div>
    </div>

</div>

<?php include("../../templates/footer.php"); ?>
