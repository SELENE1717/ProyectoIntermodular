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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
            <li> <a href="subscribe_to_destination.php">Suscribirse a un destino</a></li>
            <li> <a href="destination-list.php">Nuestros Destinos</a></li>
            <li> <a href="user-list.php">Listado de usuarios</a></li>
        </ul>
    </nav>
    <section id="header2"> 
            <div id="slogan"><h1 style="font-size: 80px;">Sugierenos<br> un destino</h1></div>
           <img src="https://i.imgur.com/dlUCxvF.png"/>
    </section>

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
