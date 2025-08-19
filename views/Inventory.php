<?php
session_start();
require_once "../system/config.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}

$successDelete = false;
$successDeleteMessage = "";
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


// Check if delid is set and not empty
if (isset($_GET['delid']) && !empty($_GET['delid'])) {
    $delid = $_GET['delid'];

    // Verificar si hay movimientos asociados al producto
    $stmtCheck = mysqli_prepare($conn, "SELECT COUNT(*) FROM movimiento WHERE id_producto = ?");
    mysqli_stmt_bind_param($stmtCheck, "i", $delid);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_bind_result($stmtCheck, $count);
    mysqli_stmt_fetch($stmtCheck);
    mysqli_stmt_close($stmtCheck);

    if ($count > 0) {
        echo "<script>alert('Este producto tiene movimientos asociados. Se eliminará y los movimientos quedarán sin referencia.');</script>";

        // Actualizar movimientos para que no referencien el producto
        $stmtUpdateMov = mysqli_prepare($conn, "UPDATE movimiento SET id_producto = NULL WHERE id_producto = ?");
        mysqli_stmt_bind_param($stmtUpdateMov, "i", $delid);
        mysqli_stmt_execute($stmtUpdateMov);
        mysqli_stmt_close($stmtUpdateMov);
    }

    // Obtener detalles del producto antes de eliminar
    $productDetailsQuery = "SELECT * FROM producto WHERE id_producto = ?";
    $stmtDetails = mysqli_prepare($conn, $productDetailsQuery);
    mysqli_stmt_bind_param($stmtDetails, "i", $delid);

    if (mysqli_stmt_execute($stmtDetails)) {
        $resultDetails = mysqli_stmt_get_result($stmtDetails);
        $productDetails = mysqli_fetch_assoc($resultDetails);
        mysqli_stmt_close($stmtDetails);

        // Eliminar el producto
        $stmtDelete = mysqli_prepare($conn, "DELETE FROM producto WHERE id_producto = ?");
        mysqli_stmt_bind_param($stmtDelete, "i", $delid);

        if (mysqli_stmt_execute($stmtDelete)) {
            $successDelete = true;
            $successDeleteMessage = "Producto eliminado!";
        } else {
            echo "<script>alert('Error deleting product: " . mysqli_error($conn) . "');</script>";
        }

        mysqli_stmt_close($stmtDelete);
    } else {
        echo "<script>alert('Error fetching product details: " . mysqli_error($conn) . "');</script>";
    }
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
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/picturefill/2.3.1/picturefill.min.js"></script>
    <script src="https://cdn.rawgit.com/sachinchoolur/lightgallery.js/master/dist/js/lightgallery-all.min.js"></script>
    <link rel="stylesheet"
        href="https://cdn.rawgit.com/sachinchoolur/lightgallery.js/master/dist/css/lightgallery.min.css">
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <script src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

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
                    <div class="col-md-12" style="display:flex;">

                        <h2 class="mt-3 mt-md-0">
                            <ion-icon name="file-tray-full" style="font-size:1.5em;"></ion-icon> Inventario
                        </h2>


                    </div>
                </div>


                <?php if ($successDelete): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert" style="margin-top:20px;">
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

                <?php if ($successEdit): ?>
                <div class="alert alert-success d-flex align-items-center" role="alert" style="margin-top:20px;">
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

                <div class="row">
                    <div class="col-md-4">
                        <select name="category" id="category" class="form-control" onchange="filterTable()"
                            style="margin-bottom:10px;">
                            <option value="">Todas las categorias</option>
                            <?php
                                // Fetch category names from the category table
                                $categoryQuery = mysqli_query($conn, "SELECT id_categoria, nombre FROM categoria");
                                
                                // Check if the query was successful
                                if ($categoryQuery) {
                                    while ($row = mysqli_fetch_assoc($categoryQuery)) {
                                        echo "<option value='" . $row['nombre'] . "'>" . $row['nombre'] . "</option>";
                                    }  
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4">
                        <select name="supplier" id="supplier" class="form-control" onchange="filterTable()"
                            style="margin-bottom:10px;">
                            <option value="">Todos los proveedores</option>
                            <?php
                                // Fetch category names from the category table
                                $supplierQuery = mysqli_query($conn, "SELECT id_proveedor, nombre FROM proveedor");
                                
                                // Check if the query was successful
                                if ($supplierQuery) {
                                    while ($row = mysqli_fetch_assoc($supplierQuery)) {
                                        echo "<option value='" . $row['nombre'] . "'>" . $row['nombre'] . "</option>";
                                    }  
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="row" id="lightgallery">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="inventory"
                                style="border-top: 1px solid #dee2e6; border-left: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                                <thead>
                                    <th style="width:60px;">ID</th>
                                    <th style="width:80px;">Imagen</th>
                                    <th style="width:100px;">Nombre</th>
                                    <th style="width:80px;">Código</th>
                                    <th style="width:80px;">Categoría</th>
                                    <th style="width:100px;">Proveedor</th>
                                    <th style="width:100px;">Precio Unitario</th>
                                    <th style="width:80px;">Stock Actual</th>
                                    <th style="width:80px;">Stock Mínimo</th>
                                    <th style="max-width:90px;">Acciones</th>
                                </thead>
                                <tbody>
                                    <?php
                                        
                                        if(isset($_GET['page_no']) && $_GET['page_no']!=""){
                                            $page_no=$_GET['page_no'];
                                        }else{
                                            $page_no=1;
                                        }

                                        $sql=mysqli_query($conn, "SELECT * FROM producto");
                                        $count=1;
                                        $row=mysqli_num_rows($sql);
                                        if($row >0){
                                            while($row =mysqli_fetch_array($sql)){
                                    ?>
                                    <tr style="vertical-align: middle;">
                                        <td><?php echo $row['id_producto'];?></td>
                                        <td><img src="<?php echo $row['imagen'];?>" alt="Product Image"
                                                onclick="toggleImageSize(this)" class="zoomable-image"
                                                style="text-align:center; width:50px; height:50;"></td>
                                        <td><?php echo $row['nombre'];?></td>
                                        <td><?php echo $row['codigo'];?></td>


                                        <?php
                                            $cat_id = $row['id_categoria'];
                                            $cat_query = mysqli_query($conn, "SELECT nombre FROM categoria WHERE id_categoria = $cat_id");
                                            $cat_row = mysqli_fetch_assoc($cat_query);
                                            $cat_name = $cat_row ? $cat_row['nombre'] : 'Sin categoría';
                                            ?>
                                        <td><?php echo $cat_name; ?></td>

                                        <?php
                                            $cat_id = $row['id_proveedor'];
                                            $cat_query = mysqli_query($conn, "SELECT nombre FROM proveedor WHERE id_proveedor = $cat_id");
                                            $cat_row = mysqli_fetch_assoc($cat_query);
                                            $cat_name = $cat_row ? $cat_row['nombre'] : 'Sin categoría';
                                            ?>
                                        <td><?php echo $cat_name; ?></td>
                                        <td><?php echo $row['precio_unitario'];?></td>
                                        <td><?php echo $row['stock_actual'];?></td>
                                        <td><?php echo $row['stock_minimo'];?></td>


                                        <td>
                                            <a href="../controllers/editproduct.php?editid=<?php echo htmlentities($row['id_producto']);?>"
                                                class="btn btn-sm" style="background-color:#1988F5; margin-right:5px;">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                            <?php
                                                if ($rol === "admin") {
                                                ?>
                                            <a href="Inventory.php?delid=<?php echo htmlentities($row['id_producto']); ?>"
                                                onClick="return confirm('Are you sure you want to delete this product?');"
                                                class="btn btn-danger btn-sm">
                                                <span class="glyphicon glyphicon-trash"></span>
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </a>
                                            <?php
                                                }
                                            ?>

                                        </td>
                                    </tr>

                                    <?php
                                    $count=$count+1;
                                }
                            }
                            ?>
                                </tbody>
                                <div class="d-flex justify-content-end mb-3">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalReporteInventario">
                                        <ion-icon name="document-text-outline"></ion-icon> Generar Reporte
                                    </button>
                                </div>
                            </table>
                        </div>

                        <div class="modal fade" id="modalReporteInventario" tabindex="-1"
                            aria-labelledby="modalReporteInventarioLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="../controllers/reporte_inventario.php" method="POST" target="_blank">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalReporteInventarioLabel">Generar Reporte de
                                                Inventario</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="id_categoria" class="form-label">Categoría</label>
                                                    <select class="form-select" name="id_categoria">
                                                        <option value="">Todas</option>
                                                        <?php
                                                            $categorias = mysqli_query($conn, "SELECT id_categoria, nombre FROM categoria");
                                                            while($c = mysqli_fetch_assoc($categorias)){
                                                                echo "<option value='{$c['id_categoria']}'>{$c['nombre']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="id_proveedor" class="form-label">Proveedor</label>
                                                    <select class="form-select" name="id_proveedor">
                                                        <option value="">Todos</option>
                                                        <?php
                                                            $proveedores = mysqli_query($conn, "SELECT id_proveedor, nombre FROM proveedor");
                                                            while($p = mysqli_fetch_assoc($proveedores)){
                                                                echo "<option value='{$p['id_proveedor']}'>{$p['nombre']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="stock" class="form-label">Stock</label>
                                                    <select class="form-select" name="stock">
                                                        <option value="">Todos</option>
                                                        <option value="bajo">Solo bajo stock</option>
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



                    </div>
                </div>
            </div>
        </div>

    </section>


    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../JS/index.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


    <script>
    $(document).ready(function() {
        var table = $('#inventory').DataTable();

        $('#category').on('change', function() {
            var selectedCategory = $(this).val();
            if (selectedCategory) {
                table.column(4).search('^' + selectedCategory + '$', true, false).draw();
            } else {
                table.column(4).search('').draw(); // Limpiar el filtro
            }
        });
    });

    $(document).ready(function() {
        var table = $('#inventory').DataTable();

        $('#supplier').on('change', function() {
            var selectedSupplier = $(this).val();
            if (selectedSupplier) {
                table.column(5).search('^' + selectedSupplier + '$', true, false).draw();
            } else {
                table.column(5).search('').draw(); // Limpiar el filtro
            }
        });
    });


    function generateBarcode() {
        var productId = $row['id'];
        var barcodeContainer = document.getElementById('barcodeContainer');

        // Use an image tag to display the barcode
        barcodeContainer.innerHTML = '<img src="barcode/barcode.php?text=' + productId + '" alt="Barcode">';
    }

    function generatePDF() {
        // Generate the barcode before creating the PDF
        generateBarcode();

        // Use html2pdf to convert the content to a PDF
        var element = document.getElementById('print-section');
        html2pdf(element);

        // Optionally, you can save or download the generated PDF
        // html2pdf(element, { filename: 'your_filename.pdf', output: 'save' });
    }

    // Call the function initially to display the barcode
    generateBarcode();

    // Attach an event listener to update the barcode when the product ID changes
    document.getElementById('product_id').addEventListener('input', generateBarcode);
    </script>

</body>

</html>