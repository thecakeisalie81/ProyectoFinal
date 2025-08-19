<?php
session_start();
require_once "../system/config.php"; // Include your database connection file

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}

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


if (isset($_POST['delete_account'])) {
    // Get user inputs
    $selectedUsername = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if (empty($selectedUsername) || empty($password)) {
        header("Location: deleteaccount.php?error=Please select a username and enter the password");
        exit();
    }

    // Fetch user details based on the selected username
    $getUserQuery = "SELECT * FROM usuarios WHERE nombre = ?";
    $stmt = mysqli_prepare($conn, $getUserQuery);
    mysqli_stmt_bind_param($stmt, "s", $selectedUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $row['contra'])) {
            // Password is correct, proceed with account deletion
        
            // Prepare the query to delete the user
            $deleteUserQuery = "DELETE FROM usuarios WHERE nombre = ?";
            $stmtDelete = mysqli_prepare($conn, $deleteUserQuery);
        
            // Bind parameters and execute the deletion query
            mysqli_stmt_bind_param($stmtDelete, "s", $selectedUsername);
            mysqli_stmt_execute($stmtDelete);
        
            // Close the prepared statement
            mysqli_stmt_close($stmtDelete);
        
            // Redirect or perform other actions after successful deletion
            $_SESSION['successDel'] = true; // Set successEdit to true
            $_SESSION['successDelMessage'] = 'El registro se ha eliminado correctamente.';
            echo "<script>document.location='../controllers/editaccount.php';</script>";
        } else {
            // Incorrect password, redirect with an error message and retain the username
            header("Location: deleteaccount.php?error=Contraseña incorrecta para el usuario &username=" . urlencode($selectedUsername));
            exit();
        }
        
    } else {
        // User not found
        header("Location: deleteaccount.php?error=Selected username not found");
        exit();
    }

    // Close the statements
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/testin3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Admin Dashboard</title>
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
                        <a href="../controllers/editaccount.php" style="font-size: 24px; text-decoration:none; color:black;">
                            <ion-icon name="arrow-back-outline"></ion-icon>
                        </a>
                        <form class="loginform" method="post">
                            <p class="loginform-title">Eliminar tu cuenta</p>
                            <?php
                        if (isset($_GET['error'])) {
                            echo "<p class='error'>" . $_GET['error'] . "</p>";
                        }
                        ?>

                            <div class="input-container">
                                <input type="text" name="username" id="username" class="form-control" required
                                    value="<?php echo htmlspecialchars($_GET['username'] ?? ''); ?>" readOnly>
                            </div>


                            <div class="input-container">
                                <input type="password" name="password" placeholder="Ingresar Contraseña"
                                    class="form-control" required>
                            </div>

                            <button type="submit" class="submit" name="delete_account">Delete Account</button>
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