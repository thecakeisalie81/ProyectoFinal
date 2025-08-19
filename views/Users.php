<?php
session_start();
require_once "../system/config.php";

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
                    <div class="col-md-12 col-offset-2">

                        <div class="box">

                            <h2>
                                <ion-icon ion-icon name="people-outline"></ion-icon> Usuarios
                            </h2>


                            <h4>Cuentas</h4>
                            <p>Manejo de cuentas, vea, agregre y elimine cuentas.</p>
                            <div class="accountbox">
                                <div class="settingTitle">
                                    <p><strong>Ver cuentas</strong> </p>
                                </div>

                                <div style="display:flex; margin">
                                    <p>Ve todas las cuentas disponibles y realiza modificaciones a estas.</p>
                                    <a href="../controllers/editaccount.php" class="allButton"
                                        style="margin-left:auto; background-color:#4F46E5; border-color:#4F46E5;"><b>Ver</b></a>
                                </div>
                                <hr>

                                <div class="settingTitle">
                                    <p><strong>Agregar cuenta</strong></p>
                                </div>
                                <div style="display:flex; ">
                                    <p>Añade una cuenta nueva.</p>
                                    <a href="../controllers/AddAccount.php" class="allButton"
                                        style="margin-left: auto; background-color:#4F46E5; border-color:#4F46E5;"><b>Añadir</b></a>

                                </div>
                            </div>


                        </div>
                        <br>
                        <br>



                    </div>
                </div>
            </div>
        </div>
    </section>


<script src="../JS/index.js"></script>
</body>

</html>