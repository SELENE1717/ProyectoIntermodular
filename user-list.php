<?php
session_start();
include 'database.php'; 

$usuarios = [];
$res = $pdo->query("SELECT email, nombre, apellidos, edad FROM USUARIOS");
$usuarios = $res->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">


    <title>Listado-Usuarios</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/listado.css">
    
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
            <?php if (isset($_SESSION['id_usuario'])): ?>
            <div id="boton-editar">
                <p><a href="edit-user.php">editar perfil</a></p> <!--esto hace que si estamos loggeados podamos verlo y editar nuestro perfil, si no, no se muestra -->
            </div>
            <?php endif; ?>
            <section id="lista-usuarios">

            <div class="tabla-usuarios">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Email</th>
                            <th>Edad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['nombre']) ?></td>
                                <td><?= htmlspecialchars($u['apellidos']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['edad']) ?></td>
                            </tr>
                       
                        <?php endforeach; ?>
                    </tbody>
                </table>


            </div>

                
            </section>
       

    </div>


</body>
</html>
