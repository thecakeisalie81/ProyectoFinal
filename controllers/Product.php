<?php
session_start();
require_once "../system/config.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}
// Initialize variables
$errorOccurred = false;
$errorMessage = "";
$success = false;
$successMessage = "";

if (isset($_POST['addproduct'])) {
    function validate($data) {
        // Trim whitespace
        $data = trim($data);
        // Remove slashes
        $data = stripslashes($data);
        // Convert special characters to HTML entities
        $data = htmlspecialchars($data);
        return $data;
    }

    $product_name = validate($_POST['nombre']);
    $product_code = validate($_POST['codigo']);
    $product_category = validate($_POST['categoria']);
    $product_supplier = validate($_POST['proveedor']);
    $unit_price = validate($_POST['precio_unitario']);
    $stock = validate($_POST['stock_actual']);
    $stock_min = 5;
    $movement  = "entrada";
    $user_id = $_SESSION['id_usuario'];
    

        $uploadDirectory = '../assets/img/';
        $uploadedFileName = $_FILES['imagen']['name'];
        $uploadedFileTemp = $_FILES['imagen']['tmp_name'];
        $uploadedFilePath = $uploadDirectory . $uploadedFileName;

        if (move_uploaded_file($uploadedFileTemp, $uploadedFilePath)) {
            $addeddate = date('Y-m-d H:i:s');

            $stmt = mysqli_prepare($conn, "INSERT INTO producto (nombre, codigo, id_categoria, id_proveedor, precio_unitario, stock_actual, stock_minimo, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssiidiis", $product_name, $product_code, $product_category, $product_supplier, $unit_price, $stock, $stock_min, $uploadedFilePath);

            mysqli_stmt_execute($stmt); // ← ¡Esta línea falta!

            $actionDescription = "Added to inventory";

            $product_id = mysqli_insert_id($conn);

            
            $stmtInsertReport = mysqli_prepare($conn, "INSERT INTO movimiento (id_producto, tipo_movimiento, cantidad, id_usuario, nombre, fecha_movimiento) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
            mysqli_stmt_bind_param($stmtInsertReport, "isiis", $product_id, $movement, $stock, $user_id, $product_name);
            
            mysqli_stmt_execute($stmtInsertReport); // ← También falta


            $success = true;
            $successMessage = "Producto añadido!";
        } else {
            echo "<script>alert('Error uploading file');</script>";
        }

        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmtInsertReport);
    
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/testin3.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.15.0/font/bootstrap-icons.css" rel="stylesheet">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Auto-todo</title>
    <script>
    function previewImage(event) {
        var input = event.target;
        var preview = document.getElementById('imagePreview');

        var reader = new FileReader();
        reader.onload = function() {
            preview.src = reader.result;
        };

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
    </script>
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
                        <div class="page-header">
                            <h2 class="mt-3 mt-md-0">
                                <ion-icon name="add-circle" style="font-size:1.5em;"></ion-icon> Nuevo producto
                            </h2>
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

                            <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img"
                                    aria-label="Success:">
                                    <use xlink:href="#check-circle-fill" />
                                </svg>
                                <div>
                                    <?php echo $successMessage; ?>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    style="margin-left:auto;"></button>
                            </div>
                            <?php endif; ?>



                            <form class="row g-3" method="POST" enctype="multipart/form-data" id="productForm">
                                <div class="col-md-6">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" required
                                        maxlength="20">
                                </div>

                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre del Producto</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" required
                                        maxlength="50">
                                </div>

                                <div class="col-md-6">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <?php
                                        
                                        $sql = "SELECT id_categoria, nombre FROM categoria";
                                        $result = $conn->query($sql);

                                        echo "<select name='categoria' id='categoria' class='form-select' required>";
                                        echo "<option value=''>Seleccione una categoria</option>";

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row["id_categoria"]) . "'>" . htmlspecialchars($row["nombre"]) . "</option>";
                                            }
                                        } else {
                                            echo "<option>No hay categorias disponibles</option>";
                                        }

                                        echo "</select>";


                                    ?>
                                </div>


                                <div class="col-md-6">
                                    <label for="proveedor" class="form-label">Proveedor</label>

                                    <?php
                                        
                                        $sql = "SELECT id_proveedor, nombre FROM proveedor";
                                        $result = $conn->query($sql);

                                        echo "<select name='proveedor' id='proveedor' class='form-select' required>";
                                        echo "<option value=''>Seleccione un proveedor</option>";

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<option value='" . htmlspecialchars($row["id_proveedor"]) . "'>" . htmlspecialchars($row["nombre"]) . "</option>";
                                            }
                                        } else {
                                            echo "<option>No hay proveedores disponibles</option>";
                                        }

                                        echo "</select>";


                                        
                                    ?>

                                </div>

                                <div class="col-md-6">
                                    <label for="precio_unitario" class="form-label">Precio Unitario</label>
                                    <input type="number" step="0.01" name="precio_unitario" id="precio_unitario"
                                        class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="stock_actual" class="form-label">Stock Actual</label>
                                    <input type="number" name="stock_actual" id="stock_actual" class="form-control"
                                        required>
                                </div>

                                <div class="col-md-6">
                                    <label for="imagen" class="form-label">Imagen del Producto</label>
                                    <input type="file" name="imagen" id="imagen" class="form-control"
                                        onchange="previewImage(event)" accept="image/*" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Vista previa</label><br>
                                    <img id="imagePreview" alt="Vista previa de la imagen"
                                        style="width: 100%; max-height: 200px; object-fit: contain;">
                                </div>




                                <div class="col-md-5">

                                    <div class="col-md-12">
                                        <button type="submit" name="addproduct" class="allButton">
                                            <b>Añadir</b></button>

                                        <button type="button" class="allButton" onclick="clearForm()"
                                            style="background-color:red; border-color:red;">
                                            <b>Limpiar</b>
                                        </button>

                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    </section>
    <script>
    function clearForm() {
        // Get all form elements
        var form = document.getElementById("productForm");

        // Reset text inputs, number inputs, and textarea
        var inputs = form.querySelectorAll("input[type=text], input[type=number], textarea");
        inputs.forEach(function(input) {
            input.value = "";
        });

        // Reset file input
        var fileInput = form.querySelector("input[type=file]");
        fileInput.value = "";

        // Reset select
        var select = form.querySelector("select");
        select.selectedIndex = 0; // Assuming the first option is the default option

        // Reset category dropdown
        var categorySelect = form.querySelector("select[name=category]");
        categorySelect.selectedIndex = 0; // Assuming the first option is the default option

        // Reset image preview
        var imagePreview = document.getElementById("imagePreview");
        imagePreview.src = ""; // Set the source to an empty string
    }

    function toggleVariantInput() {
        var variantInput = document.getElementById('variant');

        // Toggle visibility by changing the display style property
        variantInput.style.display = variantInput.style.display === 'none' ? 'block' : 'none';

        // Set focus to the input field if it's visible
        if (variantInput.style.display !== 'none') {
            variantInput.focus();
        }
    }
    </script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../JS/index.js"></script>
</body>

</html>