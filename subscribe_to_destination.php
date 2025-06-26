<?php
include 'database.php';
session_start();

$mensaje = '';
$destinos = [];
$usuarios = [];

// Obtener destinos
$res = $pdo->query("SELECT id_destino, ciudad, pais FROM DESTINO");
$destinos = $res->fetchAll(PDO::FETCH_ASSOC);

// Obtener usuarios
$res2 = $pdo->query("SELECT id_usuario, email FROM USUARIOS");
$usuarios = $res2->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $id_destino = $_POST['id_destino'];

    // Verificar si el destino requiere pasaporte --> en el enunciado ponía que había que validar si el usuario tenia pasaporte
    //se me habia pasado hacerlo, por eso agrando el codigo
    $stmt = $pdo->prepare("SELECT requiere_pasaporte FROM DESTINO WHERE id_destino = ?");
    $stmt->execute([$id_destino]);
    $requiere_pasaporte = $stmt->fetchColumn();

    if ($requiere_pasaporte) {
        // Verificar si el usuario tiene pasaporte 
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM PASAPORTE WHERE id_usuario = ?"); //aqui busca el pasaporte segun el id_usuario que busque en el select
        $stmt->execute([$id_usuario]);
        $tiene_pasaporte = $stmt->fetchColumn();

        if ($tiene_pasaporte == 0) {
            $mensaje = "Este destino requiere pasaporte"; //sale verde el mensaje pero creo que no pasa nada aunque sea un error
            //asi reeutilizamos codigo
        } else {
            // Insertar suscripción
            $stmt = $pdo->prepare("INSERT INTO SE_SUSCRIBE (id_usuario, id_destino) VALUES (:id_usuario, :id_destino)"); //si todo va bien se suscribe
            $stmt->execute([
                ':id_usuario' => $id_usuario,
                ':id_destino' => $id_destino
            ]);
            $mensaje = "Suscripción registrada correctamente.";
        }
    } else {
        // Insertar suscripción si no se requiere pasaporte
        $stmt = $pdo->prepare("INSERT INTO SE_SUSCRIBE (id_usuario, id_destino) VALUES (:id_usuario, :id_destino)");
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_destino' => $id_destino
        ]);
        $mensaje = "Suscripción registrada correctamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Suscribirse a Destino</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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
            <li> <a href="create_destination.php">Crear Destino</a></li>
            <li> <a href="registerguide.php">Creación de Guías</a></li>
            <li> <a href="subscribe_to_destination.php">Suscribirse a un destino</a></li>
            <li> <a href="destination-list.php">Nuestros Destinos</a></li>
            <li> <a href="user-list.php">Listado de usuarios</a></li>
        </ul>
    </nav>

    <div class="main">
        <section id="FormularioSuscripcion">
            <form action="#" method="post" id="formulario-suscripcion">
                <fieldset>
                    <legend>Suscribirse a destino</legend>
                    <label>Usuario (email):</label>
                    <select name="id_usuario">
                        <option value="">-- Seleccione un usuario --</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id_usuario'] ?>">
                                <?= htmlspecialchars($u['email']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>
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
