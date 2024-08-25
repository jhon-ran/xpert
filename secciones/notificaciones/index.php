<?php 
include("../../bd.php"); 
//se inicializa variable de sesión
session_start();

//TOURS******************************
//query para obtener los tours creados hoy
//$sentencia = $conexion->prepare("SELECT * FROM tours WHERE DATE(fechaCreacion) = CURRENT_DATE;");
$sentencia = $conexion->prepare("SELECT
    t.id,
    t.titulo,
    t.creador,
    u.nombre,
    u.apellidos
FROM
    tours t
JOIN
    usuarios u
ON
    t.creador = u.id
WHERE
    DATE(t.fechaCreacion) = CURRENT_DATE;
");    
$sentencia->execute();
$tours_hoy = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//query para obtener los tours creados yer
$sentencia = $conexion->prepare("SELECT
    t.id,
    t.titulo,
    t.creador,
    u.nombre,
    u.apellidos
FROM
    tours t
JOIN
    usuarios u
ON
    t.creador = u.id
WHERE
    DATE(t.fechaCreacion) = CURRENT_DATE - INTERVAL 1 DAY;
");
$sentencia->execute();
$tours_ayer = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//query para obtener los tours creados hace una semana (2-7 días atrás)
$sentencia = $conexion->prepare("SELECT
    t.id,
    t.titulo,
    t.creador,
    u.nombre,
    u.apellidos
FROM
    tours t
JOIN
    usuarios u
ON
    t.creador = u.id
WHERE
    t.fechaCreacion >= CURRENT_DATE - INTERVAL 7 DAY
    AND t.fechaCreacion < CURRENT_DATE - INTERVAL 1 DAY;
");
$sentencia->execute();
$tours_semana = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//**********CUPONES******************************
//query para obtener los cupones creados hoy
//$sentencia = $conexion->prepare("SELECT * FROM cupones WHERE DATE(fechaCreacion) = CURRENT_DATE;");
$sentencia = $conexion->prepare("SELECT
    c.id,
    c.nombre as Cnombre,
    c.creador,
    u.nombre,
    u.apellidos
FROM
    cupones c
JOIN
    usuarios u
ON
    c.creador = u.id
WHERE
    DATE(c.fechaCreacion) = CURRENT_DATE;
");
$sentencia->execute();
$cupones_hoy = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//query para obtener los cupones creados yer
//$sentencia = $conexion->prepare("SELECT * FROM cupones WHERE DATE(fechaCreacion) = CURRENT_DATE - INTERVAL 1 DAY;");
$sentencia = $conexion->prepare("SELECT
    c.id,
    c.nombre as Cnombre,
    c.creador,
    u.nombre,
    u.apellidos
FROM
    cupones c
JOIN
    usuarios u
ON
    c.creador = u.id
WHERE
    DATE(c.fechaCreacion) = CURRENT_DATE - INTERVAL 1 DAY;
");
$sentencia->execute();
$cupones_ayer = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//query para obtener los cupones creados hace una semana (2-7 días atrás)
//$sentencia = $conexion->prepare("SELECT * FROM cupones WHERE fechaCreacion >= CURRENT_DATE - INTERVAL 7 DAY AND fechaCreacion < CURRENT_DATE - INTERVAL 1 DAY;");
$sentencia = $conexion->prepare("SELECT
    c.id,
    c.nombre as Cnombre,
    c.creador,
    u.nombre,
    u.apellidos
FROM
    cupones c
JOIN
    usuarios u
ON
    c.creador = u.id
WHERE
      c.fechaCreacion >= CURRENT_DATE - INTERVAL 7 DAY
    AND c.fechaCreacion < CURRENT_DATE - INTERVAL 1 DAY;
");
$sentencia->execute();
$cupones_semana = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//**********USUARIOS******************************
//query para obtener los usuarios creados hoy
$sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE DATE(fecha) = CURRENT_DATE;");
$sentencia->execute();
$usuarios_hoy = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//query para obtener los usuarios creados yer
$sentencia = $conexion->prepare("SELECT * 
FROM usuarios 
WHERE DATE(fecha) = CURRENT_DATE - INTERVAL 1 DAY;
");
$sentencia->execute();
$usuarios_ayer = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//query para obtener los usuarios creados hace una semana (2-7 días atrás)
$sentencia = $conexion->prepare("SELECT * 
FROM usuarios 
WHERE fecha >= CURRENT_DATE - INTERVAL 7 DAY 
  AND fecha < CURRENT_DATE - INTERVAL 1 DAY;
");
$sentencia->execute();
$usuarios_semana = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//**********ASIGNACIONES DE CUPONES******************************
//query para obtener las asignaciones de cupones creadas hoy
$sentencia = $conexion->prepare("SELECT 
    usuarios_cupones.id AS association_id,
    usuarios.id AS user_id,
    usuarios.nombre AS user_name,
    usuarios.apellidos AS user_last,
    cupones.nombre AS cupon_name,
    cupones.id AS cupon_id
FROM 
    usuarios_cupones
LEFT JOIN 
    usuarios ON usuarios.id = usuarios_cupones.id_usuario
LEFT JOIN 
    cupones ON cupones.id = usuarios_cupones.id_cupon
WHERE DATE(fecha_asignacion) = CURRENT_DATE;");

$sentencia->execute();
$asignaciones_hoy = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//query para obtener las asignaciones de cupones creadas ayer
$sentencia = $conexion->prepare("SELECT 
    usuarios_cupones.id AS association_id,
    usuarios.id AS user_id,
    usuarios.nombre AS user_name,
    usuarios.apellidos AS user_last,
    cupones.nombre AS cupon_name,
    cupones.id AS cupon_id
FROM 
    usuarios_cupones
LEFT JOIN 
    usuarios ON usuarios.id = usuarios_cupones.id_usuario
LEFT JOIN 
    cupones ON cupones.id = usuarios_cupones.id_cupon
WHERE DATE(fecha_asignacion) = CURRENT_DATE - INTERVAL 1 DAY;");
$sentencia->execute();
$asignaciones_ayer = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//query para obtener las asignaciones de cupones creadas hace una semana (2-7 días atrás)
$sentencia = $conexion->prepare("SELECT 
    usuarios_cupones.id AS association_id,
    usuarios.id AS user_id,
    usuarios.nombre AS user_name,
    usuarios.apellidos AS user_last,
    cupones.nombre AS cupon_name,
    cupones.id AS cupon_id
FROM 
    usuarios_cupones
LEFT JOIN 
    usuarios ON usuarios.id = usuarios_cupones.id_usuario
LEFT JOIN 
    cupones ON cupones.id = usuarios_cupones.id_cupon
WHERE fecha_asignacion >= CURRENT_DATE - INTERVAL 7 DAY 
  AND fecha_asignacion < CURRENT_DATE - INTERVAL 1 DAY;");
$sentencia->execute();
$asignaciones_semana = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>
<br>
<div class="container">

          <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
              <!-- Bg -->
              <div class="pt-20 rounded-top" style="background:url(https://bootdey.com/image/480x480/0dcaf0/000000) no-repeat; background-size: cover;">
              </div>
              <div class="card rounded-bottom smooth-shadow-sm">
                <div class="d-flex align-items-center justify-content-between
                  pt-4 pb-6 px-4">
                  <!-- avatar -->
                  <div class="d-flex align-items-center">
                    <!-- content -->
                    <div class="lh-1">
                      <h1 class="mb-0">Notificaciones de sistema
                        <a href="#!" class="text-decoration-none">
                        </a>
                      </h1>

                    </div>
                  </div>
                 
                </div>
                <!-- nav -->
                <ul class="nav nav-lt-tab px-4" id="pills-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" href="#">Actividad reciente</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- page content -->
          <div class="py-6">
            <div class="row">
              <div class="offset-lg-1 col-lg-10 col-md-12 col-12">
                <!-- row -->
                <div class="row align-items-center mb-6">
                  <div class="col-lg-6 col-md-12 col-12">
                    <!-- form -->
                  </div>
                  <div class="col-lg-6 col-md-12 col-12 d-flex justify-content-end">
                    <!-- form -->
                  </div>
                </div>
                <!-- hr -->

                <div class="mb-8">
                  <!-- card -->
                  <div class="card bg-gray-300 shadow-none mb-4">
                    <!-- card body -->
                    <div class="card-body">
                      <div class="d-flex justify-content-between
                        align-items-center">
                        <div>
                          <h5 class="mb-0">Hoy</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- card -->
                  <div class="card-noti">
                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($tours_hoy as $tour){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium"><?php echo $tour['nombre'], ' ', $tour["apellidos"]?> creo el tour <a href="#!"><?php echo $tour['titulo']?></a></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/tours/">Ver tours</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>

                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($cupones_hoy as $cupon){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium"><?php echo $cupon['nombre'], ' ', $cupon["apellidos"]?> creó el cupón <a href="#!"><?php echo $cupon['Cnombre']?></a></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/cupones">Ver cupones</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>

                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($asignaciones_hoy as $asignacion){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium">Se le asignó el cupón <a href="#!"><?php echo $asignacion['cupon_name']?></a> a <?php echo $asignacion['user_name'], ' ', $asignacion["user_last"]?></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/cupones_usuarios/index.php">Ver asignaciones</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>
                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($usuarios_hoy as $usuario){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium">Se registró <a href="#!"><?php echo $usuario['nombre'], ' ', $usuario["apellidos"]?></a></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/usuarios/">Ver usuarios</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>
                    </ul>
                  </div>
                </div>
                <div class="mb-8">
                        <!-- card -->
                  <div class="card bg-gray-300 shadow-none mb-4">
                          <!-- card body -->
                    <div class="card-body">
                      <div class="d-flex justify-content-between
                        align-items-center">
                        <div>
                          <h5 class="mb-0">Ayer</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                        <!-- card -->
                  <div class="card-noti">
                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                        <?php foreach($cupones_ayer as $cupon){ ?>
                          <!-- list group item  -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                             <!-- content  -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium"><?php echo $cupon['nombre'], ' ', $cupon["apellidos"]?> creó
                                el cupón <a href="#!"><?php echo $cupon['Cnombre']?></a></p>
                            </div>
                          </div>
                          <div>
                             <!-- dropdown  -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivitySeven" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivitySeven">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/cupones">Ver cupones</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <?php }?>

                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($asignaciones_ayer as $asignacion){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium">Se le asignó el cupón <a href="#!"><?php echo $asignacion['cupon_name']?></a> a <?php echo $asignacion['user_name'], ' ', $asignacion["user_last"]?></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/cupones_usuarios/index.php">Ver asignaciones</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>

                    <?php foreach($tours_ayer as $tour){ ?>
                        <!-- list group item  -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                             <!-- content  -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium"><?php echo $tour['nombre'], ' ', $tour["apellidos"]?> creó
                                el tour <a href="#!"><?php echo $tour['titulo']?></a></p>
                            </div>
                          </div>
                          <div>
                             <!-- dropdown  -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivitySeven" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivitySeven">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/tours/">Ver tours</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <?php }?>
                    
                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($usuarios_ayer as $usuario){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium">Se registró <a href="#!"><?php echo $usuario['nombre'], ' ', $usuario["apellidos"]?></a></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/usuarios/">Ver usuarios</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>
                    </ul>
                  </div>
                </div>
                <div class="mb-8">
                   <!-- card  -->
                  <div class="card bg-gray-300 shadow-none mb-4">
                     <!-- card body  -->
                    <div class="card-body">
                      <div class="d-flex justify-content-between
                        align-items-center">
                        <div>
                          <h5 class="mb-0">Hace una semana</h5>
                        </div>
                      </div>
                    </div>
                  </div>
                   <!-- card  -->
                  <div class="card-noti">
                     <!-- list group  -->
                    <ul class="list-group list-group-flush">

                    <?php foreach($tours_semana as $tour){ ?>
                       <!-- list group item  -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                             <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium">Jorge Salas creo el tour <a href="#!"><?php echo $tour['titulo']?></a></p>
                            </div>
                          </div>
                          <div>
                             <!-- dropdown  -->
                            <div class="dropdown dropstart">
                              <a href="#!" id="dropdownactivityTen" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-ghost btn-sm btn-icon rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityTen">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/tours/">Ver tours</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <?php }?>

                      <!-- list group  -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($cupones_semana as $cupon){ ?>
                       <!-- list group item  -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                             <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium"><?php echo $cupon['nombre'], ' ', $cupon["apellidos"]?> creó el cupon <a href="#!"><?php echo $cupon['Cnombre']?></a></p>
                            </div>
                          </div>
                          <div>
                             <!-- dropdown  -->
                            <div class="dropdown dropstart">
                              <a href="#!" id="dropdownactivityTen" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-ghost btn-sm btn-icon rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityTen">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/cupones">Ver cupones</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <?php }?>

                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($asignaciones_semana as $asignacion){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium">Se le asignó el cupón <a href="#!"><?php echo $asignacion['cupon_name']?></a> a <?php echo $asignacion['user_name'], ' ', $asignacion["user_last"]?></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/cupones_usuarios/index.php">Ver asignaciones</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>

                    <!-- list group -->
                    <ul class="list-group list-group-flush">
                    <?php foreach($usuarios_semana as $usuario){ ?>
                      <!-- list group item -->
                      <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between
                          align-items-center">
                          <div class="d-flex align-items-center">
                                  <!-- content -->
                            <div class="ms-3">
                              <p class="mb-0
                                font-weight-medium">Se registró <a href="#!"><?php echo $usuario['nombre'], ' ', $usuario["apellidos"]?></a></p>
                            </div>
                          </div>
                          <div>
                                  <!-- dropdown -->
                            <div class="dropdown dropstart">
                              <a href="#!" class="btn btn-ghost btn-icon btn-sm rounded-circle" id="dropdownactivityOne" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical icon-xs"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                              </a>
                              <div class="dropdown-menu" aria-labelledby="dropdownactivityOne">
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo $url_base;?>secciones/usuarios/">Ver usuarios</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    <?php }?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>