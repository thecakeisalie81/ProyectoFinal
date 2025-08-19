<?php
session_start();
require_once "../system/config.php";
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}
$error = "";

$id_usuario = (int) $_SESSION['id_usuario'];
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Obtener datos
        if ($row = $resultado->fetch_assoc()) {
            $rol = $row['rol'];

        } else {
            echo "Usuario no encontrado.";
        }

if($rol === "usuario"){
    header("Location: ../No.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addaccount"])) {
    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmpassword"];
    $hashpassword=password_hash($password,PASSWORD_DEFAULT);
    $email = $_POST["email"];
    $rol = $_POST["rol"];
    
    
    $checkDuplicateQuery = mysqli_query($conn, "SELECT nombre FROM usuarios WHERE nombre = '$username'");
    if (mysqli_num_rows($checkDuplicateQuery) > 0) {
        // Product ID already exists, show an error message or take appropriate action
        echo "<script>alert('Error: Username already exists, please enter another a different username.');</script>";
    } else {
    // Check if password and confirm password match
    if ($password == $confirmPassword) {
        // Hash the password before storing it in the database
      

        // Insert the data into the users table
        $insertQuery = "INSERT INTO usuarios (nombre, correo, contra, rol, fecha_creacion) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hashpassword, $rol);

        if (mysqli_stmt_execute($stmt)) {
            // User added successfully
            echo "<script>alert('Account added successfully!');  window.location.href='../controllers/editaccount.php';</script>";
           
        } else {
            // Error adding user
            $error = "Error adding account: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        // Password and confirm password do not match
        $error = "Error: Password and confirm password do not match.";
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/testin3.css">
    <link rel="stylesheet" href="../assets/css/users.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php
        include '../includes/sidebar_admin.php';
    ?>

    <section class="dashboard">
        <div class="top">
            <ion-icon class="navToggle" name="menu-outline"></ion-icon>
        </div>

        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-offset-2">
                        <a href="../views/Users.php" style="font-size: 24px; text-decoration:none; color:black;">
                            <ion-icon name="arrow-back-outline"></ion-icon>
                        </a>
                        <form class="loginform" method="POST">
                            <p class="loginform-title">Añadir cuenta</p>
                            <?php if ($error) { ?>
                            <p class="error"><?php echo $error; ?></p>
                            <?php } ?>
                            <div class="input-container">
                                <input required="" type="text" placeholder="Ingresa el nombre de usuario"
                                    class="form-control" name="username">
                            </div>

                            <div class="input-container">
                                <input required="" type="text" placeholder="Correo electronico" class="form-control"
                                    name="email">
                            </div>

                            <label for="rol">Selecciona tu rol:</label>
                            <select id="rol" name="rol">
                                <option value="usuario">usuario</option>
                                <option value="admin">admin</option>
                            </select>

                            <div class="input-container">
                                <input required="" type="password" placeholder="Ingresa la contraseña" class="form-control"
                                    name="password">
                            </div>

                            <div class="input-container">
                                <input required="" type="password" placeholder="Confirmar contraseña" class="form-control"
                                    name=confirmpassword>
                            </div>

                            <button class="submit" name="addaccount">Agregar</button>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script src="../JS/index.js"></script>
</body>

</html>