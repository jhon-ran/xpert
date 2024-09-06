<?php
include("../../bd.php");

try {
    if (isset($_GET['tipo'])) {
        $tipo = $_GET['tipo'];

        if ($tipo == 'usuarios') {
            $query = "SELECT u.id, u.nombre, u.apellidos, u.email, u.fecha, uh.fechaModificacion, um.nombre as modificador,
                        um.apellidos as modificadorApe
                      FROM usuarios u
                      LEFT JOIN usuarios_historial uh ON u.id = uh.id_usuario
                      LEFT JOIN usuarios um ON uh.modificador = um.id";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Usuarios</h2>";
            echo "<div class='card my-2'>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive-lg'>";
            echo "<table class='table' id='tabla_id'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Fecha de creación</th>
                            <th>Fecha de modificación</th>
                            <th>Modificado por</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($usuarios as $usuario) {
                echo "<tr>
                        <td>{$usuario['id']}</td>
                        <td>{$usuario['nombre']}</td>
                        <td>{$usuario['apellidos']}</td>
                        <td>{$usuario['email']}</td>
                        <td>" . date("d/m/Y H:i", strtotime($usuario['fecha'])) . "</td>
                        <td>" . (!empty($usuario['fechaModificacion']) ? date("d/m/Y H:i", strtotime($usuario['fechaModificacion'])) : '') . "</td>
                        <td>{$usuario['modificador']} {$usuario['modificadorApe']}</td>
                      </tr>";
            }
            
            echo "  </tbody>
                  </table>
                  </div>
                  </div>
                  </div>";
        } elseif ($tipo == 'cupones') {
            $query = "SELECT c.id, c.nombre, c.descuento, c.inicioValidez, c.terminoValidez, c.fechaCreacion, ch.fechaModificacion, uc.nombre as creador, uc.apellidos as creadorApe, um.nombre as modificador, um.apellidos as modificadorApe
                      FROM cupones c
                      LEFT JOIN cupones_historial ch ON c.id = ch.id_cupon
                      LEFT JOIN usuarios uc ON c.creador = uc.id
                      LEFT JOIN usuarios um ON ch.modificador = um.id";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $cupones = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Cupones</h2>";
            echo "<div class='card my-2'>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive-lg'>";
            echo "<table class='table' id='tabla_id'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descuento</th>
                            <th>Inicio validez</th>
                            <th>Término validez</th>
                            <th>Fecha de creación</th>
                            <th>Fecha de modificación</th>
                            <th>Creado por</th>
                            <th>Modificado por</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($cupones as $cupon) {
                echo "<tr>
                        <td>{$cupon['id']}</td>
                        <td>{$cupon['nombre']}</td>
                        <td>{$cupon['descuento']}</td>
                        <td>" . date("d/m/Y H:i", strtotime($cupon['inicioValidez'])) . "</td>
                        <td>" . date("d/m/Y H:i", strtotime($cupon['terminoValidez'])) . "</td>
                        <td>" . date("d/m/Y H:i", strtotime($cupon['fechaCreacion'])) . "</td>
                        <td>" . (!empty($cupon['fechaModificacion']) ? date("d/m/Y H:i", strtotime($cupon['fechaModificacion'])) : '') . "</td>
                        <td>{$cupon['creador']} {$cupon['creadorApe']}</td>
                        <td>{$cupon['modificador']} {$cupon['modificadorApe']}</td>
                      </tr>";
            }

            echo "  </tbody>
                  </table>
                  </div>
                  </div>
                  </div>";
        } elseif ($tipo == 'tours') {
            $query = "SELECT t.id, t.titulo, t.duracion, t.capacidad, t.fechaCreacion, th.fechaModificacion, uc.nombre as creador, uc.apellidos as creadorApe, um.nombre as modificador, um.apellidos as modificadorApe
                      FROM tours t
                      LEFT JOIN tours_historial th ON t.id = th.id_tour
                      LEFT JOIN usuarios uc ON t.creador = uc.id
                      LEFT JOIN usuarios um ON th.modificador = um.id";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Tours</h2>";
            echo "<div class='card my-2'>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive-lg'>";
            echo "<table class='table' id='tabla_id'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tour</th>
                            <th>Duración</th>
                            <th>Capacidad</th>
                            <th>Fecha de creación</th>
                            <th>Fecha de modificación</th>
                            <th>Creado por</th>
                            <th>Modificado por</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($tours as $tour) {
                echo "<tr>
                        <td>{$tour['id']}</td>
                        <td>{$tour['titulo']}</td>
                        <td>{$tour['duracion']}</td>
                        <td>{$tour['capacidad']}</td>
                        <td>" . date("d/m/Y H:i", strtotime($tour['fechaCreacion'])) . "</td>
                        <td>" . (!empty($tour['fechaModificacion']) ? date("d/m/Y H:i", strtotime($tour['fechaModificacion'])) : '') . "</td>
                        <td>{$tour['creador']} {$tour['creadorApe']}</td>
                        <td>{$tour['modificador']} {$tour['modificadorApe']}</td>
                      </tr>";
            }

            echo "  </tbody>
                  </table>
                  </div>
                  </div>
                  </div>";
        } elseif ($tipo == 'cupones_usuarios') {
            $query = "SELECT cu.id, cu.nombre as nombreCupon, u.nombre as nombreUsuario, u.apellidos as apellidosUsuario, uc.fecha_asignacion
                      FROM usuarios_cupones uc
                      LEFT JOIN cupones cu ON uc.id_cupon = cu.id
                      LEFT JOIN usuarios u ON uc.id_usuario = u.id";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $cuponesUsuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Cupones Asignados a Usuarios</h2>";
            echo "<div class='card my-2'>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive-md'>";
            echo "<table class='table' id='tabla_id'>
                    <thead>
                        <tr>
                            <th>ID Cupón</th>
                            <th>Cupón</th>
                            <th>Usuario asignado</th>
                            <th>Fecha de asignación</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($cuponesUsuarios as $cuponUsuario) {
                echo "<tr>
                        <td>{$cuponUsuario['id']}</td>
                        <td>{$cuponUsuario['nombreCupon']}</td>
                        <td>{$cuponUsuario['nombreUsuario']} {$cuponUsuario['apellidosUsuario']}</td>
                        <td>" . date("d/m/Y H:i", strtotime($cuponUsuario['fecha_asignacion'])) . "</td>
                      </tr>";
            }
            echo "  </tbody>
                  </table>
                  </div>
                  </div>
                  </div>";
        } elseif ($tipo == 'cupones_tours') {
            $query = "SELECT t.id, cu.nombre as nombreCupon, t.titulo as nombreTour, t.fechaCreacion as fecha_asignacion
                      FROM tours t
                      JOIN cupones cu ON t.id_cupon = cu.id";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $cuponesTours = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Cupones Asignados a Tours</h2>";
            echo "<div class='card my-2'>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive-md'>";
            echo "<table class='table' id='tabla_id'>
                    <thead>
                        <tr>
                            <th>ID Cupón</th>
                            <th>Nombre Cupón</th>
                            <th>Tour Asignado</th>
                            <th>Fecha de Asignación</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($cuponesTours as $cuponTour) {
                echo "<tr>
                        <td>{$cuponTour['id']}</td>
                        <td>{$cuponTour['nombreCupon']}</td>
                        <td>{$cuponTour['nombreTour']}</td>
                        <td>" . date("d/m/Y H:i", strtotime($cuponTour['fecha_asignacion'])) . "</td>
                      </tr>";
            }

            echo "  </tbody>
                  </table>
                  </div>
                  </div>
                  </div>";
        } elseif ($tipo == 'tours_likes') {
            $query = "SELECT t.id as id_tour, t.titulo as nombreTour, u.id as id_usuario, CONCAT(u.nombre, ' ', u.apellidos) as nombreUsuario, l.fechaLike
                      FROM likes l
                      LEFT JOIN tours t ON l.id_tour = t.id
                      LEFT JOIN usuarios u ON l.id_usuario = u.id";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $likesTours = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h2>Tours con Likes</h2>";
            echo "<div class='card my-2'>";
            echo "<div class='card-body'>";
            echo "<div class='table-responsive-md'>";
            echo "<table class='table' id='tabla_id'>
                    <thead>
                        <tr>
                            <th>ID Tour</th>
                            <th>Tour</th>
                            <th>ID usuario</th>
                            <th>Usuario</th>
                            <th>Fecha de like</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($likesTours as $like) {
                echo "<tr>
                        <td>{$like['id_tour']}</td>
                        <td>{$like['nombreTour']}</td>
                        <td>{$like['id_usuario']}</td>
                        <td>{$like['nombreUsuario']}</td>
                        <td>" . date("d/m/Y H:i", strtotime($like['fechaLike'])) . "</td>
                      </tr>";
            }

            echo "  </tbody>
                  </table>
                  </div>
                  </div>
                  </div>";
        }
    }
} catch (Exception $ex) {
    echo "Error al conectarse a la base de datos: " . $ex->getMessage();
}
?>

