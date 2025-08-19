<?php
session_start();
require_once "../system/config.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}

$successDelete=false;
$successDeleteMessage="";
$successEdit = isset($_SESSION['successEdit']) ? $_SESSION['successEdit'] : false;
$successEditMessage = isset($_SESSION['successEditMessage']) ? $_SESSION['successEditMessage'] : "";

unset($_SESSION['successEdit']);
unset($_SESSION['successEditMessage']);

if(isset($_GET['delid'])){
    
    $delid = $_GET['delid'];
    
    echo "ID to delete: " . $delid;
    $stmt = mysqli_prepare($conn, "DELETE FROM movimiento WHERE id_movimiento =?");
    mysqli_stmt_bind_param($stmt, "s", $delid);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $successDelete=true;
        $successDeleteMessage="Movimiento eliminado de forma satisfactoria del historial!";
    } else {
        echo "<script>alert('Error deleting category: " . mysqli_error($conn) . "');</script>";
    }

    mysqli_stmt_close($stmt);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/testin3.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <title>Document</title>
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


    <!-- Aquí comienza el contenido principal -->

    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-offset-2">
                    <div class="page-header">
                        <h2 class="mt-3 mt-md-0">
                            <ion-icon name="reader" style="font-size:1.5em;"></ion-icon> Historial de movimientos
                        </h2>
                    </div>

                    <!-- Contenedor del mensaje -->

                    <!-- ALERTA DE ÉXITO -->

                    <div class="col-md-12">
                        <?php if ($successDelete): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
                                <use xlink:href="#check-circle-fill" />
                            </svg>
                            <div>
                                <?php echo $successDeleteMessage; ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                style="margin-left:auto;"></button>
                        </div>
                    </div>
                    <?php endif; ?>

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


                    <div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" style="margin-top:20px;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <th>ID</th>
                                            <th>Producto</th>
                                            <th>ID usuario</th>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if(isset($_GET['page_no']) && $_GET['page_no']!=""){
                                                    $page_no=$_GET['page_no'];
                                                }else{
                                                    $page_no=1;
                                                }

                                                $total_records_per_page=8;
                                                $offset=($page_no-1)* $total_records_per_page;
                                                $previous_page=$page_no-1;
                                                $next_page=$page_no+1;
                                                $adjacents="2";

                                                $result_count=mysqli_query($conn,"SELECT COUNT(*) as total_records FROM movimiento");
                                                $total_records=mysqli_fetch_array($result_count);
                                                $total_records=$total_records['total_records'];
                                                $total_no_of_pages=ceil($total_records / $total_records_per_page);
                                                $second_last=$total_no_of_pages-1;


                                                $sql=mysqli_query($conn, "SELECT * FROM movimiento LIMIT $offset, $total_records_per_page");
                                                $count=1;
                                                $row=mysqli_num_rows($sql);
                                                if($row >0){
                                                    while($row =mysqli_fetch_array($sql)){
                                                        ?>
                                            <tr>
                                                <td><?php echo $row['id_movimiento'];?></td>
                                                <td><?php echo $row['nombre'];?></td>
                                                <?php
                                                    $cat_id = $row['id_usuario'];
                                                    $cat_query = mysqli_query($conn, "SELECT nombre FROM usuarios WHERE id_usuario = $cat_id");
                                                    $cat_row = mysqli_fetch_assoc($cat_query);
                                                    $cat_name = $cat_row ? $cat_row['nombre'] : 'Sin usuario';
                                                    ?>
                                                <td><?php echo $cat_name; ?></td>
                                                <td><?php echo $row['tipo_movimiento'];?></td>
                                                <td><?php echo $row['cantidad'];?></td>
                                                <td><?php echo $row['fecha_movimiento'];?></td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="History.php?delid=<?php echo htmlentities($row['id_movimiento']); ?>"
                                                            onClick="return confirm('¿Seguro que deseas eliminar esta movimiento?');"
                                                            class="btn btn-danger btn-sm">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </a>
                                                    </div>
                                                </td>

                                            </tr>

                                            <?php
                                    $count=$count+1;
                                }
                            }
                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalReportes">
                                        <ion-icon name="document-text-outline"></ion-icon> Generar Reporte
                                    </button>
                                </div>

                                <!-- 📑 Modal para filtros de reportes -->
                                <div class="modal fade" id="modalReportes" tabindex="-1"
                                    aria-labelledby="modalReportesLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="../controllers/reporte_movimientos.php" method="POST" target="_blank">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalReportesLabel">Generar Reporte de
                                                        Movimientos</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label for="fecha_inicio" class="form-label">Fecha
                                                                inicio</label>
                                                            <input type="date" class="form-control" name="fecha_inicio"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="fecha_fin" class="form-label">Fecha fin</label>
                                                            <input type="date" class="form-control" name="fecha_fin"
                                                                required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="tipo_movimiento" class="form-label">Tipo de
                                                                movimiento</label>
                                                            <select class="form-select" name="tipo_movimiento">
                                                                <option value="">Todos</option>
                                                                <option value="entrada">Entrada</option>
                                                                <option value="salida">Salida</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="id_usuario" class="form-label">Usuario</label>
                                                            <select class="form-select" name="id_usuario">
                                                                <option value="">Todos</option>
                                                                <?php
                                                                    $usuarios = mysqli_query($conn, "SELECT id_usuario, nombre FROM usuarios");
                                                                    while($u = mysqli_fetch_assoc($usuarios)){
                                                                        echo "<option value='{$u['id_usuario']}'>{$u['nombre']}</option>";
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="generarPDF" class="btn btn-success">
                                                        <ion-icon name="download-outline"></ion-icon> Descargar PDF
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <ul class="pagination justify-content-end mt-3">
                                    <li class="page-item disabled">
                                        <span class="page-link">Showing page
                                            <?php echo $page_no . " of " . $total_no_of_pages; ?></span>
                                    </li>

                                    <li class="page-item <?php if ($page_no <= 1) echo 'disabled'; ?>">
                                        <a class="page-link"
                                            <?php if ($page_no > 1) echo "href='?page_no=$previous_page'"; ?>>Previous</a>
                                    </li>

                                    <?php
                                        if ($total_no_of_pages <= 10) {
                                            for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                                                echo "<li class='page-item ";
                                                echo ($counter == $page_no) ? "active'>" : "'>";
                                                echo "<a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                            }
                                        } elseif ($total_no_of_pages > 10) {
                                            if ($page_no <= 4) {
                                                for ($counter = 1; $counter <= 7; $counter++) {
                                                    echo "<li class='page-item ";
                                                    echo ($counter == $page_no) ? "active'>" : "'>";
                                                    echo "<a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                                }
                                                echo "<li class='page-item'><span class='page-link'>...</span></li>";
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                            } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                                                echo "<li class='page-item'><span class='page-link'>...</span></li>";

                                                for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                                    echo "<li class='page-item ";
                                                    echo ($counter == $page_no) ? "active'>" : "'>";
                                                    echo "<a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                                }

                                                echo "<li class='page-item'><span class='page-link'>...</span></li>";
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                            } else {
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                                                echo "<li class='page-item'><span class='page-link'>...</span></li>";

                                                for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                                    echo "<li class='page-item ";
                                                    echo ($counter == $page_no) ? "active'>" : "'>";
                                                    echo "<a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                                }
                                            }
                                        }
                                        ?>

                                    <li class="page-item <?php if ($page_no >= $total_no_of_pages) echo 'disabled'; ?>">
                                        <a class="page-link"
                                            <?php if ($page_no < $total_no_of_pages) echo "href='?page_no=$next_page'"; ?>>Next</a>
                                    </li>

                                    <?php if ($page_no < $total_no_of_pages) {
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>Last &rsaquo; &rsaquo;</a></li>";
                                    } ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    </div>






    </div>

</section>
<script src="../JS/index.js"></script>

<script>
function confirmDelete() {
    var confirmed = confirm('Are you sure you want to delete this product?');
    console.log('Confirmation:', confirmed);

    if (confirmed) {
        <?php
session_start();
require_once "config.php";

if(isset($_GET['delid'])){
    $delid = $_GET['delid'];
    echo "ID to delete: " . $delid;
    $stmt = mysqli_prepare($conn, "DELETE FROM category WHERE categoryid=?");
    mysqli_stmt_bind_param($stmt, "s", $delid);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
      
    } else {
        echo "<script>alert('Error deleting category: " . mysqli_error($conn) . "');
</script>";
}

mysqli_stmt_close($stmt);
}
?>
}
}
</script>
</body>

</html>