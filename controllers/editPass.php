<?php
session_start();
require_once "../system/config.php"; // Include your database connection file

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}

$rawUsername = urldecode($_GET['editid'] ?? '');
$username = htmlspecialchars($rawUsername);
$errorOccurred = false;
$errorMessage = "";

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

    if ($rol !== 'admin') {
        $username = $row['nombre'];
    }

if (isset($_POST['update_account'])) {
    // Get user inputs
    $selectedUsername = $_POST['username'];
    $oldPassword = $_POST['oldpassword'];
    $newPassword = $_POST['newpassword'];
    $confirmPassword = $_POST['confirmpassword'];

    // Validate inputs
    if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        header("Location: editPass.php?username={$selectedUsername}&error=Por favor complete todos los campos");
        exit();
    }

    // Fetch user details based on the selected username
    $getUserQuery = "SELECT * FROM usuarios WHERE nombre = ?";
    $stmt = mysqli_prepare($conn, $getUserQuery);
    mysqli_stmt_bind_param($stmt, "s", $selectedUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify old password
        if (password_verify($oldPassword, $row['contra'])) {
            // Old password is correct, proceed with further checks

            // Check if new password and confirm password match
            if ($newPassword !== $confirmPassword) {
                header("Location: edit_account.php?username={$selectedUsername}&error=Las contraseñas no coinciden");
                exit();
            }

            // Update the user's password in the database
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePasswordQuery = "UPDATE usuarios SET contra = ? WHERE nombre = ?";
            $stmtUpdate = mysqli_prepare($conn, $updatePasswordQuery);
            mysqli_stmt_bind_param($stmtUpdate, "ss", $hashedNewPassword, $selectedUsername);
            mysqli_stmt_execute($stmtUpdate);

            // Close the prepared statements
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($stmtUpdate);
            mysqli_close($conn);

            $_SESSION['successEdit'] = true; // Set successEdit to true
            $_SESSION['successEditMessage'] = 'El registro se ha actualizado correctamente.';
            echo "<script>document.location='../controllers/editaccount.php';</script>";

            // Redirect or perform other actions after successful password update
            header("Location: editaccount.php?username={$selectedUsername}");
            exit();
        } else {
            // Incorrect old password, redirect with an error message and retain the username
            $errorOccurred = true;
        $errorMessage = "Contraseña antigua incorrecta. Por favor, inténtelo de nuevo.";

        }
    } else {
        // User not found
        header("Location: edit_account.php?username={$selectedUsername}&error=Selected username not found");
        exit();
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/testin3.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Admin Dashboard</title>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path
                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>
</head>

<body>
    <?php 
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

        if($rol === "admin"){
        include '../includes/sidebar_admin.php'; 
        }else if($rol === "usuario"){
            include '../includes/sidebar_usuario.php'; 
        }
    ?>

    <section class="dashboard">
        <div class="top">
            <ion-icon class="navToggle" name="menu-outline"></ion-icon>
        </div>

        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-offset-2">
                        <?php
                            if($rol === "admin"){
                                echo '<a href="../controllers/editaccount.php" style="font-size: 24px; text-decoration:none; color:black;">
                            <ion-icon name="arrow-back-outline"></ion-icon>
                            </a>';}
                        ?>
                        
                        <form class="loginform" style="width:600px;" method="post">
                            <p class="loginform-title">Cambiar contraseña</p>

                            <?php if ($errorOccurred): ?>
                            <div class="alert alert-danger d-flex align-items-center alert-dismissible" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                    aria-label="Danger:">
                                    <use xlink:href="#exclamation-triangle-fill" />
                                </svg>
                                <div>
                                    <?php echo $errorMessage; ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php endif; ?>
                            <?php
                                if (isset($_GET['error'])) {
                                    echo '<div class="error-message">' . $_GET['error'] . '</div>';
                                }
                            ?>

                            <div class="input-container">
                                <input type="text" name="username" id="username" class="form-control" required
                                    value="<?php echo htmlspecialchars($_GET['username'] ?? $username); ?>" readonly>
                            </div>

                            <div class="input-container">
                                <input type="password" name="oldpassword" placeholder="Ingresa contraseña antigua"
                                    class="form-control" required>
                            </div>

                            <div class="input-container">
                                <input type="password" name="newpassword" placeholder="Ingresa nueva contraseña"
                                    class="form-control" required>
                            </div>

                            <div class="input-container">
                                <input type="password" name="confirmpassword" placeholder="Confirma nueva contraseña"
                                    class="form-control" required>
                            </div>

                            <button type="submit" class="submit" name="update_account">Actualizar cuenta</button>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../JS/index.js"></script>
</body>

</html>