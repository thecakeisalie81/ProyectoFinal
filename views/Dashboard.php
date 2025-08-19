<?php
session_start();
require_once "../system/config.php";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/testin3.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery (necesario para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Auto-Todo</title>
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
                        <h2 class="mt-3 mt-md-0">
                            <ion-icon name="stats-chart" style="font-size:1.5em;"></ion-icon> Dashboard
                        </h2>
                        
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="../controllers/editaccount.php" class="boxdash"
                                    style="background: linear-gradient(90deg, #00d2ff 0%, #3a47d5 100%);">
                                    <ion-icon name="person-circle-outline" style="font-size: 5em;"></ion-icon>
                                    <div>
                                        <h2 class="margin-top"><?php echo $c_users; ?></h2>
                                        <p class="text-muted">Usuarios</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="Category.php" class="boxdash"
                                    style="background: linear-gradient(90deg, #00C9FF 0%, #92FE9D 100%);">

                                    <ion-icon name="grid-outline" style="font-size: 5em;"></ion-icon>
                                    <div>
                                        <h2 class="margin-top"> <?php  echo $c_category; ?> </h2>
                                        <p class="text-muted">Categorias</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="Inventory.php" class="boxdash"
                                    style="background: linear-gradient(90deg, #FC466B 0%, #3F5EFB 100%);">
                                    <ion-icon name="file-tray-full-outline" style="font-size: 5em;"></ion-icon>
                                    <div>
                                        <h2 class="margin-top"> <?php  echo $c_inventory; ?> </h2>
                                        <p class="text-muted">Productos</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="suppliers.php" class="boxdash"
                                    style="background: linear-gradient(90deg, #f8ff00 0%, #3ad59f 100%);">
                                    <ion-icon name="people-circle-outline" style="font-size: 5em;"></ion-icon>
                                    <div>
                                        <h2 class="margin-top"> <?php  echo $c_suppliers; ?> </h2>
                                        <p class="text-muted">Proveedores</p>
                                    </div>
                                </a>
                            </div>

                        </div>

                        <br>
                        <div class="row" id="lightgallery">
                            <div class="col-md-6">
                                <h3>Productos con bajo stock</h3>
                                <div class="table-responsive">
                                    <table id="lowStockTable" class="table table-bordered table-striped">
                                        <thead class="alert-info">
                                            <tr>
                                                <th class="fixed-column">Codigo</th>
                                                <th class="fixed-column">Nombre</th>
                                                <th class="fixed-column">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php include '../controllers/lowstock.php' ?>
                                        </tbody>
                                    </table>
                                </div>
                                <ul class="pagination justify-content-end mt-3">
                                    <!-- Pagination links go here -->
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>



    </section>


    <script>
    $(document).ready(function () {
        $('#lowStockTable').DataTable({
            "pageLength": 5,        // cantidad de filas por página
            "lengthChange": true,   // permitir elegir cuántos registros ver
            "searching": true,      // activar buscador
            "ordering": true,       // activar ordenamiento
            "info": true,           // mostrar "Mostrando X de Y"
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    });
    </script>



    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../JS/index.js"></script>
</body>

</html>