<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="icon" href="img/logo.png">
</head>
<body>
<?php include 'header.php'; ?>

<section>
<?php
require 'conexion/conexion.php';

try {
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
  
$consulta = "SELECT li.folio, li.fecha, hb.num_habitacion, ar.nombre_area,GROUP_CONCAT(ac.nombre_actividad) AS actividades, 
em.nombres, li.hora_inicio, li.hora_fin, li.tiempo, li.mtto, li.pk_limpieza, li.observacion
FROM limpieza li
LEFT JOIN habitacion hb ON li.fk_habitacion = hb.pk_habitacion
LEFT JOIN area ar ON li.fk_area = ar.pk_area
INNER JOIN limpieza_actividad la ON la.fk_limpieza = li.pk_limpieza
INNER JOIN actividad ac ON la.fk_actividad = ac.pk_actividad
INNER JOIN empleado em ON li.fk_empleado = em.pk_empleado
WHERE li.estatus = 1 AND DATE(li.fecha) = CURDATE()
GROUP BY li.pk_limpieza";


    $stmt = $pdo->prepare($consulta);
    $stmt->execute();
    
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultados) > 0) {
        echo "<h2 class='h2listas'>Limpiezas realizadas el dia de hoy <img src='img/calendario.png' width='35' height='35'></h2>";
        echo "<hr>";
        echo "<table>";
        echo "<tr>";
        echo "<th>Folio</th>";
        echo "<th>Fecha</th>";
        echo "<th>Numero de habitación</th>";
        echo "<th>Área</th>";
        echo "<th>Actividades realizadas</th>";
        echo "<th>Relizada por</th>";
        echo "<th>Hora de inicio</th>";
        echo "<th>Hora de fin</th>";
        echo "<th>Duración</th>";
        echo "<th>¿Se requiere mantenimiento?</th>";
        echo "<th>Observaciones para mantenimiento</th>";
        echo "<th>ACCIONES</th>";
        echo "</tr>";

        foreach ($resultados as $fila) {
            echo "<tr>";
            echo "<td>{$fila['folio']}</td>";
            echo "<td>{$fila['fecha']}</td>";
            if (!empty($fila['num_habitacion'])) {
                echo "<td>{$fila['num_habitacion']}</td>";
            } else {
                echo "<td>------</td>"; 
            }
            
            if (!empty($fila['nombre_area'])) {
                echo "<td>{$fila['nombre_area']}</td>";
            } else {
                echo "<td>------</td>";
            }

            echo "<td>{$fila['actividades']}</td>"; 
            echo "<td>{$fila['nombres']}</td>";
            echo "<td>" . date("h:i a", strtotime($fila['hora_inicio'])) . "</td>";
            echo "<td>" . date("h:i a", strtotime($fila['hora_fin'])) . "</td>"; 
            echo "<td>{$fila['tiempo']}</td>";
            echo "<td>{$fila['mtto']}</td>";
            echo "<td>{$fila['observacion']}</td>";
            echo "<td>";
            $pk_limpieza = $fila ['pk_limpieza'];
            echo "<a href='editar_limpieza.php'><img src='img/actualizar.png' width='35' height='35'></a>";
            echo "<a href='./funciones/eliminar/eliminar_limpiezas.php?pk_limpieza={$pk_limpieza}'><img src='img/eliminar.png' width='35' height='35'></a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron registros.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>   


<!--Apartado botones - links-->
<section class="rgstrs">
    <a href="formulario_limpieza.php" class="reg_lpza" href="registro_limpieza.php" >
        <h3>Registrar limpieza</h3>
        <img class="img_box" src="img/lista.png" alt="logo de registro de limpieza">
    </a>
    <div class="reg_gen">
        <a href="formulario_mantenimiento.php" class="reg_mn">
            <h3>Registrar mantenimiento</h3>
            <img class="img_box" src="img/mantenimiento.png" alt="logo de registro de mantenimiento">
        </a>
        <a href="formulario_actividad.php" class="reg_ac">
            <h3>Registrar actividad</h3>
            <img class="img_box" src="img/limpieza.png" alt="logo de registro de actividad">
        </a>
    </div>
    <div class="reg_gen">
        <a href="formulario_area.php" class="reg_ar">
            <h3>Registrar área</h3>
            <img class="img_box" src="img/area.png" alt="logo de registro de area">
        </a>
        <a href="formulario_habitacion.php" class="reg_hb">
            <h3>Registrar habitación</h3>
            <img class="img_box" src="img/habitacion.png" alt="logo de registro de habitacion">
        </a>
        <a href="formulario_empleado.php" class="reg_em">
            <h3>Registrar empleado</h3>
            <img class="img_box" src="img/empleados.png" alt="logo de registro de empleado">
        </a>
    </div>
</section>
    
</body>
</html>