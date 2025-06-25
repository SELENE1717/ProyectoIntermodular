<?php
session_start();
include 'database.php'; 

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $especialidad = trim($_POST['especialidad'] ?? '');
    $destino = trim($_POST['destino'] ?? '');
    if (empty($nombre)) {
        $errors[] = "Debe completar el nombre";
    }

    if (empty($apellidos)) {
        $errors[] = "Debe completar los apellidos";
    }


    if (empty($especialidad)) {
    $errors[] = "Debe seleccionar una especialidad";
    }

    if (empty($destino)) {
        $errors[] = "Debe especificar un destino";
    }
    
$destino_nombre = trim($_POST['destino'] ?? '');

if (empty($destino_nombre)) {
    $errors[] = "Debe completar el destino";
} else {

    $stmt = $pdo->prepare("SELECT id_destino FROM DESTINOS WHERE nombre LIKE ?"); //como aun no henos hecho la parte php de destinos no sé si furula.
    $stmt->execute([$destino_nombre]);
    $destino_row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$destino_row) {
        $errors[] = "El destino especificado no existe";
    } else {
        $id_destino = $destino_row['id_destino'];
    }
}


    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO GUIA (nombre, apellidos, especialidad)  VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellidos, $especialidad, $id_destino]);

            $stmt = $pdo->prepare("INSERT INTO SE_ASIGNA (id_destino, id_guia) VALUES (?, ?)");
            $stmt->execute([$id_destino, $id_guia]);

            $_SESSION['success'] = "Guía registrado con éxito.";
            header("Location: registerguide.html"); 
            exit;

        } catch (Exception $e) {
            $errors[] = "Error al insertar guía: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/05a75ce1d7.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <title>Formulario - Guía</title>
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
            <li> <a href="subscribe_to_destination.php">Suscribirse a un destino</a><li>
            <li> <a href="destination-list.php">Nuestros Destinos</a></li>
            <li> <a href="user-list.php">Listado de usuarios</a></li>
                </ul>
            </nav>
        
        <section id="header2"> 
            <div id="slogan"><h1 style="font-size: 80px;">Colabora<br> con nosotros</h1></div>
           <img src="https://i.imgur.com/7E9q6Vl.png"/>
        </section>
        
        <div class="main">

            <section id="Formularioguia">
                <h2>¿Es un guía? Complete este formulario para colaborar con nosotros</h2>
                 <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?> <!--en este parrafo se colocan los errores detectados por parte del servidor -->
                        </div>
                 <?php endif; ?>
                        <!--si se ve mal igual lo muevo mas abajo-->
                <form action="#" method="post" id="formulario-guia">
                    
                    <fieldset>
                        <legend>Datos del guía</legend>
                         <label for="nombre">Nombre:</label>
                         <input type="text" name="nombre" id="nombre"><br>
                         <div class="error" id="error-nombre"></div>
                         <label for="apellidos">Apellidos</label>
                         <input type="text" id="apellidos" name="apellidos"><br>
                         <div class="error" id="error-apellidos"></div>
                         <label for="especialidad">Especialidad</label><!--sin querer en la version html dejé aqui edad, aqui lo corrijo-->
                         <select id="especialidad" name="especialidad"><!--tras volver a leer bien la base de datos me di cuenta de que esto tenia que ser un select porque da opciones prefijadas-->
                            <option value="">-- Selecciona una opción --</option>
                            <option value="Geografía">Geografía</option>
                            <option value="Historia">Historia</option>
                            <option value="Arquitectura">Arquitectura</option>
                            <option value="Comida">Comida</option>
                         </select><br>
                         <div class="error" id="error-especialidad"></div>
                         <label for="destino">Destino</label>
                         <input type="text" id="destino" name="destino"><br> <!--Cuando lo pasemos a php miramos como conectamos -->
                        <div class="error" id="error-destino"></div>
                    </fieldset>
                    <button id="enviar">Registrarse</button>
                </form>
                <div id="registroderecha">
                    <p>—Colaboramos con más de 1000 guías de todas partes del mundo para proporcionar a nuestros clientes las mejores experiencias—</p>
                </div>
            </section>
       

    </div>

    <script> 

    //VALIDACIÓN JS
        let nombre = document.getElementById("nombre");
        let apellidos = document.getElementById("apellidos");
        let edad = document.getElementById("especialidad");

        //recogemos los divs de los errores para llamarlos en la funcion
        let error_nombre = document.getElementById("error-nombre");
        let error_especialidad = document.getElementById("error-especialidad");
        let error_destino = document.getElementById("error-destino");
        let error_apellidos = document.getElementById("error-apellidos");


        function validar(){ //funcion que valida el formulario

            let esvalido = true;
            if (apellidos.value.trim() ===''){
                esvalido= false;
                error_apellidos.textContent = "Los apellidos son obligatorios";
            }
            if (nombre.value.trim() ===''){
                esvalido= false;
                error_nombre.textContent = "Debe completar el nombre";
            }
            if (especialidad.value.trim() === ''){
                esvalido= false;
                error_especialidad.textContent = "Debe seleccionar la especialidad";
            }if (destino.value.trim() === ''){
                esvalido= false;
                error_destino.textContent = "Debe completar el destino";
            }

            return esvalido;
        }



         let enviar = document.getElementById('enviar');
         let formulario_guia= document.getElementById('formulario-guia');

         enviar.addEventListener('click', e=>{
              e.preventDefault();
            if (validar()){ 
                formulario_guia.submit();

            }
         });

    </script>
</body>
</html>
