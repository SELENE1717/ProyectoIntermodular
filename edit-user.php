<?php
session_start();
include 'database.php';

$id_usuario = $_SESSION['id_usuario'];
$mensaje = "";


$stmt = $pdo->prepare("SELECT * FROM USUARIOS WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener pasaporte si existe
$stmt = $pdo->prepare("SELECT * FROM PASAPORTE WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$pasaporte = $stmt->fetch(PDO::FETCH_ASSOC);

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $nombre =trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $edad = trim ($_POST['edad']);
    $tienePasaporte = $_POST['pasaporte'] ?? null;

$errors = [];

      //Validación
    if (empty($email)) {
        $errors[] = "Se requiere un email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if(empty($nombre)){
    $errors[]="Se debe completar el nombre";
    }  

    if(empty($apellidos)){
    $errors[]="Se debe completar los apellidos";
    }


    
    
    if (empty($edad)) {
        $errors[] = "Se debe especificar una edad";
    } elseif (!is_numeric($edad) || (int)$edad < 18) {
        $errors[] = "Debe ser mayor de edad";
    }

    if (!isset($tienePasaporte) || ($tienePasaporte !== "1" && $tienePasaporte !== "0")) {
        $errors[] = "Debe indicar si tiene pasaporte";
    }

    // Validar datos de pasaporte si corresponde
    $numeroPasaporte = $fechaExpedicion = $fechaCaducidad = null;

    if ($tienePasaporte === "1") { //como no funcione me tiro por la ventana
        $numeroPasaporte = trim($_POST['numero_pasaporte'] ?? '');
        $fechaExpedicion = trim($_POST['fecha_expedicion'] ?? '');
        $fechaCaducidad = trim($_POST['fecha_caducidad'] ?? '');

        if (empty($numeroPasaporte)) {
            $errors[] = "Debe indicar el número de pasaporte";
        }

        if (empty($fechaExpedicion)) {
            $errors[] = "Debe indicar la fecha de expedición del pasaporte";
        }

        if (empty($fechaCaducidad)) {
            $errors[] = "Debe indicar la fecha de caducidad del pasaporte";
        } elseif ($fechaExpedicion && $fechaCaducidad <= $fechaExpedicion) {
            $errors[] = "La fecha de caducidad debe ser posterior a la de expedición";
        }
    }


    if (empty($errors)) {
    try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("UPDATE USUARIOS SET nombre = ?, apellidos = ?, edad = ?, email = ? WHERE id_usuario = ?");
    $stmt->execute([$nombre, $apellidos, $edad, $email, $id_usuario]);


    if ($tienePasaporte === "1") {
                if ($pasaporte) {
                    $stmt = $pdo->prepare("UPDATE PASAPORTE SET numero=?, fecha_expedicion=?, caducidad=? WHERE id_usuario=?");
                    $stmt->execute([$numeroPasaporte, $fechaExpedicion, $fechaCaducidad, $id_usuario]);
                }
    else {
        $stmt = $pdo->prepare("INSERT INTO PASAPORTE (id_usuario, numero, fecha_expedicion, caducidad) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_usuario, $numeroPasaporte, $fechaExpedicion, $fechaCaducidad]);
    }
    
    }  
    $pdo->commit();
$mensaje = "Tus datos se han actualizado correctamente.";}
    catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Error en la actualización " . $e->getMessage();
        }

    
    }

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
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
            <li> <a href="subscribe_to_destination.php">Suscribirse a un destino</a><li>
            <li> <a href="destination-list.php">Nuestros Destinos</a></li>
            <li> <a href="user-list.php">Listado de usuarios</a></li>
            <li> <a href="logout.php">Logout</a></li>
                </ul>
            </nav>
    <div class="main">
    <section id="editar-usuario">
    <h2>Editar Mis Datos</h2>
      <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?> <!--en este parrafo se colocan los errores detectados por parte del servidor -->
                         </div>
                 <?php endif; ?>

    <?php if ($mensaje): ?>
        <p style="color: green"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="post" id="formulario-edit">
        <label>Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>"><br><br>
        <div class="error" id="error-nombre"></div>

        <label>Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>"><br><br>
        <div class="error" id="error-apellidos"></div>
        <label>Edad:</label><br>
        <input type="number" id="edad" name="edad" value="<?= htmlspecialchars($usuario['edad']) ?>"><br><br>

        <label>Email:</label><br>
        <input id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>"><br><br>
        <div class="error" id="error-email"></div>
        <label>¿Tiene pasaporte?</label>
        <select name="pasaporte" id="pasaporte">
            <option value="1" <?= $pasaporte ? 'selected' : '' ?>>Sí</option>
            <option value="0" <?= !$pasaporte ? 'selected' : '' ?>>No</option>
        </select>
        <div class="error" id="error-pasaporte"></div>

      <div id="datos_pasaporte" style="margin-top: 10px; <?= $pasaporte ? '' : 'display:none;' ?>">
        <label>Número de Pasaporte</label>
        <input type="text" id="numero" name="numero_pasaporte" value="<?= $pasaporte['numero'] ?? '' ?>">
        <div class="error" id="error-numpass"></div>

        <label>Fecha de Expedición</label>
        <input type="date" id="fechaexpedicion" name="fecha_expedicion" value="<?= $pasaporte['fecha_expedicion'] ?? '' ?>">
        <div class="error" id="error-fechaex"></div>

        <label>Fecha de Caducidad</label>
        <input type="date" id="caducidad" name="fecha_caducidad" value="<?= $pasaporte['caducidad'] ?? '' ?>">
        <div class="error" id="error-caducidad"></div>
      </div>

        <button id="enviar">Guardar Cambios</button>
    </form>
    </div>
</div>
    </section>
 <script> 



    //VALIDACIÓN JS
        let nombre = document.getElementById("nombre");
        let apellidos = document.getElementById("apellidos");
        let edad = document.getElementById("edad");
        let email = document.getElementById("email");

        //recogemos los divs de los errores para llamarlos en la funcion
        let error_password = document.getElementById("error-password");
        let error_nombre = document.getElementById("error-nombre");
        let error_edad = document.getElementById("error-edad");
        let error_email = document.getElementById("error-email");
        let error_apellidos = document.getElementById("error-apellidos");

        let pasaporte = document.getElementById("pasaporte");
        let numero = document.getElementById("numero");
        let fechaexpedicion = document.getElementById("fechaexpedicion");
        let caducidad = document.getElementById("caducidad");

        let error_pasaporte = document.getElementById("error-pasaporte");
        let error_numpass = document.getElementById("error-numpass");
        let error_fechaex = document.getElementById("error-fechaex");
        let error_caducidad = document.getElementById("error-caducidad");

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
            if (edad.value.trim() === '' || parseInt(edad.value.trim()) < 18) {
                esvalido= false;
                error_edad.textContent = "Debe ser mayor de edad";
            }
            if (email.value.trim() === ''){
                esvalido= false;
                error_email.textContent = "Debe completar el email";
            }
             if (pasaporte.value !== "1" && pasaporte.value !== "0") {
                esvalido = false;
                error_pasaporte.textContent = "Debe seleccionar si tiene pasaporte.";
            } 

        // Si tiene pasaporte, tendrá que completar los campos obligatorios
        if (pasaporte.value === "1") {
            if (numero.value.trim() === '') {
                esvalido = false;
                error_numpass.textContent = "Debe indicar el número de pasaporte.";
            }
            if (fechaexpedicion.value.trim() === '') {
                esvalido = false;
                error_fechaex.textContent = "Debe indicar la fecha de expedición."; //esto estaba mal puesto lo he arreglado
            }
            if (caducidad.value.trim() === '') {
                esvalido = false;
                error_caducidad.textContent = "Debe indicar la fecha de caducidad."; //quiero poner que la fecha de caducidad no pueda ser anterior a la vieja, luego lo cambio
            }
        }
    
            return esvalido;
        }



         let enviar = document.getElementById('enviar');
         let formulario_edit = document.getElementById('formulario-edit');

         enviar.addEventListener('click', e=>{
              e.preventDefault();
            if (validar()){ 
                formulario_edit.submit();

            }
         });

    </script>
</body>
</html>
