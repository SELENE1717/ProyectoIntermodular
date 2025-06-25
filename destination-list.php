
<?php
session_start();
include 'database.php';

// Obtener todos los destinos
$destinos = [];
$res = $pdo->query("SELECT ciudad, pais, requiere_pasaporte FROM DESTINO");
$destinos = $res->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Destinos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/listado.css"> <!-- Nueva hoja para este listado -->
</head>
<body>
<div class="container">
    <nav class="navbar">
        <div id="izquierda">
            <div style="text-align: center; display: inline-block;">
                <img src="https://images.vexels.com/media/users/3/128926/isolated/preview/c60c97eba10a56280114b19063d04655-icono-redondo-del-aeropuerto-de-avion.png">
            </div>
            <p id="titulo">Travelway</p>
        </div>
        <ul>
            <li> <a href="index.php">Home</a></li>
            <li> <a href="registeruser.php">Registro</a></li>
            <li> <a href="login.php">Login</a></li>
            <li> <a href="create_destination.php">Crear Destino</a></li>
            <li> <a href="registerguide.php">Creación de Guías</a></li>
            <li> <a href="destination-list.php">Nuestros Destinos</a></li>
            <li> <a href="user-list.php">Listado de usuarios</a></li>
        </ul>
    </nav>

    <div class="main">
        <section id="listado-destinos">
            <h2>Listado de Destinos</h2>
            <div class="tabla-destinos">
                <table>
                    <thead>
                        <tr>
                            <th>Ciudad</th>
                            <th>País</th>
                            <th>Requiere Pasaporte</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($destinos as $d): ?>
                            <tr>
                                <td><?= htmlspecialchars($d['ciudad']) ?></td>
                                <td><?= htmlspecialchars($d['pais']) ?></td>
                                <td><?= $d['requiere_pasaporte'] ? 'Sí' : 'No' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
</body>
</html>
