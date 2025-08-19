<?php
session_start();
require_once "../system/config.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}

$successAdd = isset($_SESSION['successAdd']) ? $_SESSION['successAdd'] : false;
$successAddMessage = isset($_SESSION['successAddMessage']) ? $_SESSION['successAddMessage'] : "";

$successDel = isset($_SESSION['successDel']) ? $_SESSION['successDel'] : false;
$successDelMessage = isset($_SESSION['successDelMessage']) ? $_SESSION['successDelMessage'] : "";

unset($_SESSION['successDel']);
unset($_SESSION['successDelMessage']);

$successEdit = isset($_SESSION['successEdit']) ? $_SESSION['successEdit'] : false;
$successEditMessage = isset($_SESSION['successEditMessage']) ? $_SESSION['successEditMessage'] : "";

unset($_SESSION['successEdit']);
unset($_SESSION['successEditMessage']);


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

                        <a href="../views/Users.php" style="font-size: 24px; text-decoration:none; color:black;">
                            <ion-icon name="arrow-back-outline"></ion-icon>
                        </a>
                        <h2>View Account</h2>
                        <div class="col-md-12">
                        <?php if ($successEdit): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                <use xlink:href="#check-circle-fill" />
                            </svg>
                            <div>
                                <?php echo $successEditMessage; ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                style="margin-left:auto;"></button>
                        </div>
                        <?php endif; ?>
                    </div>
                        <div class="col-md-12">
                            <?php if ($successAdd): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                    aria-label="Success:">
                                    <use xlink:href="#check-circle-fill" />
                                </svg>
                                <div>
                                    <?php echo $successAddMessage; ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    style="margin-left:auto;"></button>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-12">
                        <?php if ($successDel): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                <use xlink:href="#check-circle-fill" />
                            </svg>
                            <div>
                                <?php echo $successDelMessage; ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                style="margin-left:auto;"></button>
                        </div>
                        <?php endif; ?>
                    </div>


                        <table class="table table-bordered table-striped">
                            <thead>
                                <th>Nombre de usuario</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                                <?php
                            // Fetch user information from the users table
                            $user_info_query = mysqli_query($conn, "SELECT * FROM usuarios");
                            
                            if ($user_info_query) {
                                while ($user_data = mysqli_fetch_assoc($user_info_query)) {
                                    echo "<tr>";
                                    echo "<td>{$user_data['nombre']}</td>";
                                    // Add edit and delete links
                                    echo "<td>
                                            <a href='editPass.php?editid={$user_data['nombre']}' class='btn btn-warning btn-sm'>Cambiar contraseña</a>
                                            <a href='deleteaccount.php?username={$user_data['nombre']}' class='btn btn-danger btn-sm'>Borrar cuenta</a>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>Error fetching user information: " . mysqli_error($conn) . "</td></tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>



    </div>
    </div>
    </div>
    </div>
    </section>
    <script src="../JS/index.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>