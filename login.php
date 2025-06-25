<?php
session_start();
include 'database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email)) {
        $errors[] = "Se debe completar el campo de email";
    }

    if (empty($password)) {
        $errors[] = "Se debe completar el campo de contraseña";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM USUARIOS WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "El email o la contraseña no son correctos.";
        }
    }
}
?><!DOCTYPE html>
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
            <li> <a href="subscribe_to_destination.php">Suscribirse a un destino</a></li>
            <li> <a href="destination-list.php">Nuestros Destinos</a></li>
            <li> <a href="user-list.php">Listado de usuarios</a></li>
                </ul>
            </nav>
        
        <section id="header2"> 
            <div id="slogan"><h1 style="font-size: 80px;">Identificate</h1></div>
           <img src="https://i.imgur.com/0gv8Q7E.png"/>
        </section>
        
        <div class="main">

            <section id="Formularioguia">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="#" method="post" id="formulario-login">
                    
                    <fieldset>
                        <legend>Iniciar sesión</legend>
                         <label for="email">Email:</label>
                         <input type="text" name="email" id="email"><br>
                         <div class="error" id="error-email"></div>
                         <label for="Contraseña">Contraseña</label>
                         <input type="password" id="password" name="password"><br>
                        <div class="error" id="error-password"></div>
                    </fieldset>
                    <button id="enviar">Iniciar sesión</button>
                </form>
            </section>
       

    </div>

    <script> 

    //VALIDACIÓN JS
        let email = document.getElementById("email");
        let password = document.getElementById("password");

        //recogemos los divs de los errores para llamarlos en la funcion
        let error_email = document.getElementById("error-email");
        let error_password = document.getElementById("error-password");


        function validar(){ //funcion que valida el formulario

            let esvalido = true;
            if (email.value.trim() ===''){
                esvalido= false;
                error_email.textContent = "Debe completar el campo de email";
            }
            if (password.value.trim() ===''){
                esvalido= false;
                error_password.textContent = "Debe completar el campo de contraseña";
            }

            return esvalido;
        }



         let enviar = document.getElementById('enviar');
         let formulario_login= document.getElementById('formulario-login');//esto lo tenia mal en el html y aqui lo he corregido

         enviar.addEventListener('click', e=>{
              e.preventDefault();
            if (validar()){ 
                formulario_login.submit();//lo mismo aqui corregido.

            }
         });

    </script>
</body>
</html>
