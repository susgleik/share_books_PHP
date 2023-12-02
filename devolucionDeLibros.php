<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        <li><a href="libros.php">Catálogo De Libros</a></li>
        <li><a href="registroDelibros.php">Registro De Libros</a></li>
        <li><a href="devolucionDeLibros.php">Préstamos De Libros</a></li>
        <li><a href="https://utp.ac.pa/">Visitar página UTP</a></li>
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

<?php

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

require_once("conexion.php");

if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Procesamiento de devolución
if (isset($_POST["devolver_libro"])) {
    $usuario_id = $_SESSION["usuario_id"];
    $libro_id = $_POST["libro_id"];

    // Realiza la devolución
    $sql_devolucion = "UPDATE transacciones SET fecha_devolucion = CURRENT_TIMESTAMP WHERE usuario_id = $usuario_id AND libro_id = $libro_id";
    if ($conn->query($sql_devolucion) === TRUE) {
        // Actualiza la disponibilidad del libro
        $update_disponibilidad = "UPDATE libros SET disponibilidad = true WHERE id = $libro_id";
        $conn->query($update_disponibilidad);
        echo "Libro devuelto con éxito.";
    } else {
        echo "Error al realizar la devolución: " . $conn->error;
    }
}
?>

<main>
    <?php
    // Consulta para obtener los libros prestados por el usuario
    $usuario_id = $_SESSION["usuario_id"];
    $sql_libros_prestados = "SELECT libros.id, libros.titulo, libros.autor, libros.imagen
                             FROM transacciones
                             JOIN libros ON transacciones.libro_id = libros.id
                             WHERE transacciones.usuario_id = $usuario_id AND transacciones.fecha_devolucion IS NULL";

    $result_libros_prestados = $conn->query($sql_libros_prestados);

    if ($result_libros_prestados->num_rows > 0) {
        while ($row = $result_libros_prestados->fetch_assoc()) {
            echo '<div class="item">';
            echo '    <figure>';
            echo '        <img src="' . $row["imagen"] . '" alt="' . $row["titulo"] . '">';
            echo '    </figure>';
            echo '    <div class="info-product">';
            echo '        <div class="info-product_info">';
            echo '            <h2>' . $row["titulo"] . '</h2>';
            echo '            <p>Autor: ' . $row["autor"] . '</p>';
            echo '        </div>';
            echo '        <form action="" method="post">';
            echo '            <input type="hidden" name="libro_id" value="' . $row["id"] . '">';
            echo '            <button type="submit" name="devolver_libro">Devolver Libro</button>';
            echo '        </form>';
            echo '    </div>';
            echo '</div>';
        }
    } else {
        echo "No tienes libros prestados actualmente.";
    }

    $conn->close();
    ?>
</main>

</body>
</html>
