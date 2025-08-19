<?php
session_start();
require_once "system/config.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}

$c_category = count_by_id('categoria');
$c_inventory = count_by_id('producto');
$c_users = count_by_id('usuarios');
$c_suppliers= count_by_id('proveedor');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, isnitial-scale=1.0">
    <link rel="stylesheet" href="assets/css/testin3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Auto-Todo</title>
</head>

<body>


    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-offset-2">

                    <div class="box">

                        <h2>
                            <ion-icon ion-icon name="close-circle"></ion-icon> Sin acceso
                        </h2>


                        <div class="accountbox">
                            <div class="settingTitle">
                                <p><strong>Usted no tiene permisos para realizar esta accion</strong> </p>
                            </div>

                            <div style="display:flex; margin">
                                <p>Solo los administradores pueden hacer uso de estas funciones.</p>
                                <a href="views/Dashboard.php" class="allButton"
                                    style="margin-left:auto; background-color:#4F46E5; border-color:#4F46E5;"><b>Volver</b></a>
                            </div>
                            <hr>
                        </div>


                    </div>
                    <br>
                    <br>



                </div>
            </div>
        </div>
    </div>



    </section>


    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="JS/index.js"></script>
</body>

</html>