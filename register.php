<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
        <div class="logoAndContainer">
            <div class="logo">
                <img src="images/logo.png" alt="Logo de la Empresa">
            </div>  
            <div class="logo-name">
                <h1>FISC BOOK SHOP</h1>
            </div>
        </div>
        <ul class="nav-list">
            
            <li><a href="libros.php ">Catalogo De Libros</a></li>
            <li><a href="registroDelibros.php">Registro De Libros</a></li>
            <li><a href="devolucionDeLibros.php">Prestamos De Libros</a></li>
            <li><a href="https://utp.ac.pa/">Visistar pagina UTP</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>

    </nav>

    <div class="headline">
        <h2>Registro de Usuario</h2>
    </div>
    <div class="div_register_form">
        <div class="register_form">
            <form action="" method="post">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required><br>
    
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required><br>
    
                <input type="submit" value="Registrar Usuario">
            </form>
            <div class="login_form-loginLink">
                <p>Si ya te registraste logueate aqui:</p>
                <a href="login.php">LOGIN</a>
            </div>
        </div>
</body>
</html>


<?php
//logica para el registro del usuario
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $check_existencia = "SELECT * FROM usuarios WHERE username = '$username'";
    $result = $conn->query($check_existencia);

    if ($result->num_rows == 0) {
        $insert_usuario = "INSERT INTO usuarios (username, password) VALUES ('$username', '$password')";

        if ($conn->query($insert_usuario) === TRUE) {
            echo "Usuario registrado con éxito.";
        } else {
            echo "Error al registrar el usuario: " . $conn->error;
        }
    } else {
        echo "El nombre de usuario ya está en uso.";
    }
}

$conn->close();
?>