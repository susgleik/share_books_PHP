<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de libros</title>
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
            <li id="logout">
            <form action="" method="post">
                <input type="submit" name="logout" value="Cerrar Sesión">
            </form>
        </li>
        </ul>

        <?php
    session_start();

    if (!isset($_SESSION["usuario_id"])) {
        header("Location: login.php"); // Redirecciona a la página de inicio de sesión si no hay sesión activa
        exit();
    }

    require_once("conexion.php");

    if (isset($_POST["logout"])) {
        session_destroy(); // Cierra la sesión
        header("Location: login.php"); // Redirige a la página de inicio de sesión
        exit();
    }
    ?>
    </nav>
       

    <div class="headline">
        <h2>Registro de Libros</h2>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br>

        <label for="autor">Autor:</label>
        <input type="text" id="autor" name="autor" required><br>

        <label for="genero">Género:</label>
        <input type="text" id="genero" name="genero" required><br>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required><br>

        <input type="submit" value="Registrar Libro">
    </form>
    
</body>
</html>

<?php
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    $genero = $_POST["genero"];

    // Guarda la imagen en una carpeta (debes crear la carpeta 'uploads' en tu proyecto)
    $imagen = $_FILES["imagen"]["name"];
    $ruta_imagen = "uploads/" . $imagen;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta_imagen);

    $sql = "INSERT INTO libros (titulo, autor, genero, imagen) VALUES ('$titulo', '$autor', '$genero', '$ruta_imagen')";

    if ($conn->query($sql) === TRUE) {
        echo "Libro registrado con éxito.";
    } else {
        echo "Error al registrar el libro: " . $conn->error;
    }
}

$conn->close();
?>