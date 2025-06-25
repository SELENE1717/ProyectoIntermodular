<?php
session_start();
include 'database.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ciudad = $_POST['ciudad'];
    $pais = $_POST['pais'];
    $requiere = $_POST['requiere_pasaporte'];

    $stmt = $pdo->prepare("INSERT INTO DESTINO (ciudad, pais, requiere_pasaporte) VALUES (?, ?, ?)");
    $stmt->execute([$ciudad, $pais, $requiere]);

    $mensaje = "Destino creado correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Destino</title>
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
        <section id="RegistroUsuario">
            <form action="#" method="post" id="formulario-registro">
                <fieldset>
                    <legend>Crear nuevo destino</legend>
                    <label>Ciudad:</label>
                    <input type="text" name="ciudad"><br>
                    <label>País:</label>
                    <input type="text" name="pais"><br>
                    <label>¿Requiere pasaporte?</label>
                    <select name="requiere_pasaporte">
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </fieldset>
                <button type="submit" id="enviar">Crear Destino</button>
            </form>

            <?php if ($mensaje): ?>
                <p style="color: green; font-weight: bold;"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>
        </section>
    </div>
</div>
</body>
</html>
