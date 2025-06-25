<?php
include 'database.php';

$mensaje = '';
$destinos = [];

$res = $conexion->query("SELECT id_destino, ciudad, pais FROM DESTINO");
while ($fila = $res->fetch_assoc()) {
    $destinos[] = $fila;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $id_destino = $_POST['id_destino'];
    $fecha = date('Y-m-d');

    $stmt = $conexion->prepare("INSERT INTO SUSCRIPCION_DESTINO (email, id_destino, fecha_suscripcion) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $email, $id_destino, $fecha);
    $stmt->execute();

    $mensaje = "Suscripción registrada correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Suscripción a Destino</title>
    <link rel="stylesheet" href="css/style.css">
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
            <li> <a href="create_destination.php">Creación de Destino</a></li>
            <li> <a href="registerguide.php">Creación de Guías</a></li>
            <li> <a href="#">Listados</a></li>
        </ul>
    </nav>

    <div class="main">
        <section id="FormularioSuscripcion">
            <form action="#" method="post" id="formulario-suscripcion">
                <fieldset>
                    <legend>Suscribirse a destino</legend>
                    <label>Email:</label>
                    <input type="text" name="email"><br>
                    <label>Destino:</label>
                    <select name="id_destino">
                        <option value="">-- Seleccione un destino --</option>
                        <?php foreach ($destinos as $d): ?>
                            <option value="<?= $d['id_destino'] ?>">
                                <?= htmlspecialchars($d['ciudad'] . ' - ' . $d['pais']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>
                <button type="submit" id="enviar">Suscribirse</button>
            </form>

            <?php if ($mensaje): ?>
                <p style="color: green; font-weight: bold;"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>
        </section>
    </div>
</div>
</body>
</html>
