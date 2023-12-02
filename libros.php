<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros</title>
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
<div class="headline">
    <h2>Libros disponibles</h2>
</div>
<main>
    
<?php
    require_once("conexion.php");

    $sql = "SELECT libros.*, COUNT(*) AS cantidad
            FROM libros
            LEFT JOIN transacciones ON libros.id = transacciones.libro_id
            WHERE (libros.disponibilidad = true OR transacciones.fecha_devolucion IS NOT NULL)
                AND (transacciones.usuario_id IS NULL OR transacciones.usuario_id = {$_SESSION["usuario_id"]})
            GROUP BY libros.id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="item">';
            echo '    <figure>';
            echo '        <img src="' . $row["imagen"] . '" alt="' . $row["titulo"] . '">';
            echo '    </figure>';
            echo '    <div class="info-product">';
            echo '        <div class="info-product_info">';
            echo '            <h2>' . $row["titulo"] . '</h2>';
            echo '            <p>Autor: ' . $row["autor"] . '</p>';
            echo '            <p>Cantidad Disponible: ' . $row["cantidad"] . '</p>';
            echo '        </div>';

            $libro_id = $row["id"];
            $check_disponibilidad = "SELECT disponibilidad FROM libros WHERE id = $libro_id";
            $result_disponibilidad = $conn->query($check_disponibilidad);

            if ($result_disponibilidad->num_rows > 0) {
                $row_disponibilidad = $result_disponibilidad->fetch_assoc();

                if ($row_disponibilidad["disponibilidad"]) {
                    // El libro está disponible, muestra el botón para tomar prestado
                    echo '        <form action="" method="post">';
                    echo '            <input type="hidden" name="libro_id" value="' . $libro_id . '">';
                    echo '            <button type="submit" name="prestar_libro">Tomar prestado</button>';
                    echo '        </form>';
                } else {
                    // El libro no está disponible, muestra el botón para devolver
                    echo '        <form action="devolucionDeLibros.php" method="post">';
                    echo '            <input type="hidden" name="libro_id" value="' . $libro_id . '">';
                    echo '            <button type="submit" name="devolver_libro">Devolver</button>';
                    echo '        </form>';
                }
            }

            echo '    </div>';
            echo '</div>';
        }
    } else {
        echo "No hay libros disponibles.";
    }

    // Procesamiento de préstamo
    if (isset($_POST["prestar_libro"])) {
        $libro_id = $_POST["libro_id"];
        $usuario_id = $_SESSION["usuario_id"];

        // Verifica si el libro está disponible antes de intentar prestar
        $check_disponibilidad = "SELECT disponibilidad FROM libros WHERE id = $libro_id";
        $result_disponibilidad = $conn->query($check_disponibilidad);

        if ($result_disponibilidad->num_rows > 0) {
            $row_disponibilidad = $result_disponibilidad->fetch_assoc();

            if ($row_disponibilidad["disponibilidad"]) {
                // Realiza el préstamo solo si el libro está disponible
                $update_disponibilidad = "UPDATE libros SET disponibilidad = false WHERE id = $libro_id";
                $conn->query($update_disponibilidad);

                // Inserta el registro en la tabla transacciones
                $insert_transaccion = "INSERT INTO transacciones (usuario_id, libro_id) VALUES ($usuario_id, $libro_id)";
                $conn->query($insert_transaccion);

                echo "Libro prestado con éxito.";

                // Recarga la página después de tomar prestado
                echo '<script>window.location.href = "libros.php";</script>';
            } else {
                echo "El libro no está actualmente disponible.";
            }
        }
    }

    $conn->close();
    ?>
</main>
</body>
</html>
