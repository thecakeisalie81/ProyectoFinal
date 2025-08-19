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



$successDelete=false;
$successDeleteMessage="";
$successEdit = isset($_SESSION['successEdit']) ? $_SESSION['successEdit'] : false;
$successEditMessage = isset($_SESSION['successEditMessage']) ? $_SESSION['successEditMessage'] : "";

unset($_SESSION['successEdit']);
unset($_SESSION['successEditMessage']);

$successAdd = isset($_SESSION['successAdd']) ? $_SESSION['successAdd'] : false;
$successAddMessage = isset($_SESSION['successAddMessage']) ? $_SESSION['successAddMessage'] : "";

unset($_SESSION['successAdd']);
unset($_SESSION['successAddMessage']);

if(isset($_GET['delid'])){
    
    $delid = $_GET['delid'];
    
    echo "ID to delete: " . $delid;
    $stmt = mysqli_prepare($conn, "DELETE FROM proveedor WHERE id_proveedor=?");
    mysqli_stmt_bind_param($stmt, "s", $delid);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $successDelete=true;
        $successDeleteMessage="Proveedor eliminado satisfactoriamente!";
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
                    <div class="page-header">   
                        <h2 class="mt-3 mt-md-0">
                            <ion-icon name="cube" style="font-size:1.5em;"></ion-icon> Proveedores
                        </h2>
                    </div>

                    <div class="col-md-12">
                        <?php if ($successAdd): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
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
                        <?php endif; ?>
                    </div>

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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" style="margin-top:20px;">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Contacto</th>
                                        <th>Direccion</th>
                                        <th>Telefono</th>
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

                            $result_count=mysqli_query($conn,"SELECT COUNT(*) as total_records FROM proveedor");
                            $total_records=mysqli_fetch_array($result_count);
                            $total_records=$total_records['total_records'];
                            $total_no_of_pages=ceil($total_records / $total_records_per_page);
                            $second_last=$total_no_of_pages-1;


                            $sql=mysqli_query($conn, "SELECT * FROM proveedor LIMIT $offset, $total_records_per_page");
                            $count=1;
                            $row=mysqli_num_rows($sql);
                            if($row >0){
                                while($row =mysqli_fetch_array($sql)){

                                    ?>
                                        <tr>
                                            <td><?php echo $row['id_proveedor'];?></td>
                                            <td><?php echo $row['nombre'];?></td>
                                            <td><?php echo $row['contacto'];?></td>
                                            <td><?php echo $row['direccion'];?></td>
                                            <td><?php echo $row['telefono'];?></td>
                                            <td>
                                                <a href="../controllers/editsupplier.php?editid=<?php echo htmlentities($row['id_proveedor']);?>"
                                                    class="btn btn-sm"
                                                    style="background-color:#1988F5; margin-right:5px;">
                                                    <ion-icon name="create-outline"></ion-icon>
                                                    <a href="suppliers.php?delid=<?php echo htmlentities($row['id_proveedor']);?>"
                                                        onClick="confirmDelete(); return false;"
                                                        class="btn btn-danger btn-sm">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </a>
                                                </a>
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

                            <a href="../controllers/addsuppliers.php" class="allButton">Agregar nuevo proveedor</a>
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