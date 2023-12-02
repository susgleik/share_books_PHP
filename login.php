<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <h2>Iniciar Sesión</h2>
    </div>
    <div class="div_login_form">
        <div class="login_form">
            <form action="" method="post">
                
        
                <div class="input_box">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="username" required><br>
                    <i class="uil uil-envelope-alt email"></i>
                </div>
        
                <div class="input_box">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required><br>
        
                </div>
        
        
                <input type="submit" value="Iniciar Sesión">
            </form>
            <div class="login_form-registerLink">
                <p>Si no tienes cuenta registrate aqui:</p>
                <a href="register.php">REGISTRO</a>
            </div>
            </div>
    </div>

    
</body>
</html>

<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $check_usuario = "SELECT * FROM usuarios WHERE username = '$username'";
    $result = $conn->query($check_usuario);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Inicio de sesión exitoso
            session_start();
            $_SESSION["usuario_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            header("Location: libros.php"); // Redirecciona a una página de bienvenida o a la página principal
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}

$conn->close();
?>