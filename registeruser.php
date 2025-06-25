<?php
session_start();
include 'database.php';

$errors = [];
//en el momento de pulsar el botón de register 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $nombre =trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $edad = trim ($_POST['edad']);
    $tienePasaporte = $_POST['pasaporte'] ?? null;


  //Validación
    if (empty($email)) {
        $errors[] = "Se requiere un email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Se requiere una contraseña";
    } elseif (strlen($password) < 5) {
        $errors[] = "La contraseña debe tener al menos 5 caracteres";
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


    // que el email exista
    $stmt = $pdo->prepare("SELECT * FROM USUARIOS WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $errors[] = "El email ya existe";
    }

       if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // Insertar usuario
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO USUARIOS (email, password, nombre, apellidos, edad) 
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$email, $hashed_password, $nombre, $apellidos, $edad]);

            $id_usuario = $pdo->lastInsertId();

            // Si tiene pasaporte, insertar en PASAPORTE
            if ($tienePasaporte === "1") {
                $stmt = $pdo->prepare("INSERT INTO PASAPORTE (numero, fecha_expedicion, caducidad, id_usuario)
                                       VALUES (?, ?, ?, ?)");
                $stmt->execute([$numeroPasaporte, $fechaExpedicion, $fechaCaducidad, $id_usuario]);
            }

            $pdo->commit();

            $_SESSION['success'] = "El registro ha sido completado con éxito. Por favor, inicie sesión.";
            header("Location: login.php");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Error en el registro: " . $e->getMessage();
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

    <title>Registro - Usuario</title>
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
                    <li> <a href="falta el link">Listados</a></li>
                </ul>
            </nav>
        
        <section id="header2"> <!--El slide no sé si cambiarlo o quitarlo para esta página -->

                <div id="slogan"><h1>Forme parte<br> de nuestra<br> comunidad</h1></div>
                <img src="https://i.imgur.com/mWBsDSt.png"/>

        </section>
        
        <div class="main">

            <section id="RegistroUsuario">
                <h2>Registrese para reservar con nosotros</h2>

                 
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?> <!--en este parrafo se colocan los errores detectados por parte del servidor -->
                         </div>
                 <?php endif; ?>

                <form action="#" method="post" id="formulario-registro">
                    
                    <fieldset>
                        <legend>Datos de la cuenta:</legend>
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email">
                        <div class="error" id="error-email"></div>
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password">
                        <div class="error" id="error-password"></div>
                    </fieldset>
                    <fieldset>
                        <legend>Datos de usuario:</legend>
                         <label for="nombre">Nombre:</label>
                         <input type="text" name="nombre" id="nombre"><br>
                         <div class="error" id="error-nombre"></div>
                         <label for="apellidos">Apellidos</label>
                         <input type="text" id="apellidos" name="apellidos"><br>
                         <div class="error" id="error-apellidos"></div>
                         <label for="edad">Edad</label>
                         <input type="text" id="edad" name="edad"><br>
                         <div class="error" id="error-edad"></div>
                         <label for="Tienepasaporte">¿Tiene pasaporte?</label> <!-- luego con los codigos en funcion de su selección deberá añadir los demás campos o no-->
                         <select  id="pasaporte" name="pasaporte">
                             <option value="1">si</option>
                             <option value="0">no</option>
                        </select>
                        <div class="error" id="error-pasaporte"></div>
                    </fieldset>
                    <fieldset>
                        <legend> Datos pasaporte (Solo si tiene pasaporte)</legend>
                        <label for="numero_pasaporte">Numero de pasaporte:</label>
                        <input type="text" name="numero_pasaporte" id="numero"> <!--he arreglado los nombres de los inputs porque estaban mal puestos-->
                        <div class="error" id="error-numpass"></div>
                        <label for="fecha_expedicion">Fecha de expedición:</label>
                        <input type="date" name="fecha_expedicion" id="fecha_expedicion">
                        <div class="error" id="error-fechaex"></div>
                        <label for="fecha_caducidad">Fecha de caducidad:</label>
                        <input name="fecha_caducidad" id="caducidad" type="date">
                        <div class="error" id="error-caducidad"></div>
                    </fieldset>
                    <button id="enviar">Registrarse</button>
                </form>
                <div id="registroderecha">
                    <p>—El 99.99% de nuestros clientes están encantados de haber reservado con nosotros y haber experimentado las mejores vacaciones de sus vidas<br> ¿A qué esperas tú?—</p>
                </div>
            </section>
       

    </div>

    <script> 



    //VALIDACIÓN JS
        let password = document.getElementById("password");
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
        let fechaexpedicion = document.getElementById("fecha_expedicion");
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
            if(password.value.trim() ===''){
                esvalido = false;
                error_password.textContent = "El password es obligatorio";
            }else if(password.value.trim().length < 5){
                esvalido = false;
                error_password.textContent ="La contraseña debe tener más de 5 letras";
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
            if (fecha_expedicion.value.trim() === '') {
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
         let formulario_registro = document.getElementById('formulario-registro');

         enviar.addEventListener('click', e=>{
              e.preventDefault();
            if (validar()){ //si el formulario es válido (true) lo manda
                formulario_registro.submit();

            }
         });

    </script>
</body>
</html>


