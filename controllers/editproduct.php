<?php
session_start();
require_once "../system/config.php";

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../Login.php");
    exit; 
}

if (isset($_POST['update'])) {
    function validate($data) {
        // Trim whitespace
        $data = trim($data);
        // Remove slashes
        $data = stripslashes($data);
        // Convert special characters to HTML entities
        $data = htmlspecialchars($data);
        return $data;
    }

    $uploadDirectory = '../assets/img/';
    $uploadedFileName = $_FILES['imagen']['name'];
    $uploadedFileTemp = $_FILES['imagen']['tmp_name'];

    $eid = isset($_GET['editid']) ? $_GET['editid'] : '';

    $producto = mysqli_query($conn, "SELECT * FROM producto WHERE id_producto='$eid'");
        $Imagen = '';
        if ($producto && $rowProducto = mysqli_fetch_array($producto)) {
            $Imagen = $rowProducto['imagen'];
        }

    

    if (!empty($uploadedFileName)) {
        // Validate the uploaded file
        $uploadedFilePath = $uploadDirectory . $uploadedFileName;

    } else {
        // If no new image is uploaded, keep the old image path
        $uploadedFilePath = $Imagen;
        
    }

    $stock_anterior = $rowProducto['stock_actual'];

    
    $product_name = $_POST['nombre'];
    $product_code = $_POST['codigo'];
    $product_category = $_POST['categoria'];
    $product_supplier = $_POST['proveedor'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $user_id = $_SESSION['id_usuario'];
    $movement  = "entrada";
    
    move_uploaded_file($uploadedFileTemp, $uploadedFilePath);

    
    $sql = "UPDATE producto SET nombre='$product_name', codigo = '$product_code', id_categoria ='$product_category', id_proveedor ='$product_supplier', precio_unitario='$price', stock_actual = '$stock', imagen = '$uploadedFilePath' WHERE id_producto='$eid'";
    


    if ($stock > $stock_anterior) {
        $move_stock = $stock - $stock_anterior;
        $stmtInsertReport = mysqli_prepare($conn, "INSERT INTO movimiento (id_producto, tipo_movimiento, cantidad, id_usuario, nombre, fecha_movimiento) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
            mysqli_stmt_bind_param($stmtInsertReport, "isiis", $eid, $movement, $move_stock, $user_id, $product_name);
            
            mysqli_stmt_execute($stmtInsertReport); // ← También falta
    }else if ($stock < $stock_anterior) {
        $movement = "salida";
        $move_stock = $stock_anterior - $stock;
        $stmtInsertReport = mysqli_prepare($conn, "INSERT INTO movimiento (id_producto, tipo_movimiento, cantidad, id_usuario, nombre, fecha_movimiento) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
            mysqli_stmt_bind_param($stmtInsertReport, "isiis", $eid, $movement, $move_stock, $user_id, $product_name);
            
            mysqli_stmt_execute($stmtInsertReport); // ← También falta
    }

     // Execute the update query

    if (mysqli_query($conn, $sql)) {
        
        $_SESSION['successEdit'] = true; // Set successEdit to true
        $_SESSION['successEditMessage'] = 'El registro se ha actualizado correctamente.';
        echo "<script>document.location='../views/Inventory.php';</script>";
    } else {
        echo "<script>alert('Algo salio mal: " . mysqli_error($conn) . "');</script>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/testin3.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
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
                        <a href="../views/Inventory.php" style="font-size: 24px; text-decoration:none; color:black;">
                            <ion-icon name="arrow-back-outline"></ion-icon>
                        </a>

                        <div class="page-header">
                            <h2>Editar informacion del producto</h2>
                        </div>

                        <div>
                            <form class="row g-3" method="POST" id="categoryForm" enctype="multipart/form-data">
                                <?php
                            $eid = isset($_GET['editid']) ? $_GET['editid'] : '';
                            $sql = mysqli_query($conn, "SELECT * FROM producto WHERE id_producto='$eid'");

                            
                            if (!$sql) {
                                // Output the error message
                                die("Error: " . mysqli_error($conn));
                            }
                            while ($row = mysqli_fetch_array($sql)) {
                            ?>



                                <div class="col-md-6">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" name="codigo" value="<?php echo $row['codigo'];?>"
                                        id="product_code" class="form-control" required="" placeholder="">
                                </div>

                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" name="nombre" value="<?php echo $row['nombre'];?>"
                                        id="product_code" class="form-control" required="" placeholder="">
                                </div>

                                <div class="col-md-6">
                                    <label for="supplier_name" class="form-label">Stock</label>
                                    <input type="text" name="stock" value="<?php echo $row['stock_actual'];?>"
                                        id="category_name" class="form-control" required="" placeholder="">
                                </div>

                                <div class="col-md-6">
                                    <label for="price" class="form-label">Precio unitario</label>
                                    <input type="text" name="price" value="<?php echo $row['precio_unitario'];?>"
                                        id="category_name" class="form-control" required="" placeholder="">
                                </div>

                                <?php } ?>

                                <div class="col-md-6">
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <?php
                                        $eid = isset($_GET['editid']) ? $_GET['editid'] : '';
                                        $producto = mysqli_query($conn, "SELECT * FROM producto WHERE id_producto='$eid'");
                                        $categoriaSeleccionada = '';
                                        if ($producto && $rowProducto = mysqli_fetch_array($producto)) {
                                            $categoriaSeleccionada = $rowProducto['id_categoria'];
                                        }

                                        $sql = "SELECT id_categoria, nombre FROM categoria";
                                        $result = $conn->query($sql);

                                        echo "<select name='categoria' id='categoria' class='form-select' required>";
                                        echo "<option value=''>Seleccione una categoria</option>";

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row["id_categoria"] == $categoriaSeleccionada) ? "selected" : "";
                                                echo "<option value='" . htmlspecialchars($row["id_categoria"]) . "' $selected>" . htmlspecialchars($row["nombre"]) . "</option>";
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
                                        $eid = isset($_GET['editid']) ? $_GET['editid'] : '';
                                        $producto = mysqli_query($conn, "SELECT * FROM producto WHERE id_producto='$eid'");
                                        $proveedorSeleccionado = '';
                                        if ($producto && $rowProducto = mysqli_fetch_array($producto)) {
                                            $proveedorSeleccionado = $rowProducto['id_proveedor'];
                                        }

                                        $sql = "SELECT id_proveedor, nombre FROM proveedor";
                                        $result = $conn->query($sql);

                                        echo "<select name='proveedor' id='proveedor' class='form-select' required>";
                                        echo "<option value=''>Seleccione un proveedor</option>";

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $selected = ($row["id_proveedor"] == $proveedorSeleccionado) ? "selected" : "";
                                                echo "<option value='" . htmlspecialchars($row["id_proveedor"]) . "' $selected>" . htmlspecialchars($row["nombre"]) . "</option>";
                                            }
                                        } else {
                                            echo "<option>No hay proveedores disponibles</option>";
                                        }

                                        echo "</select>";
                                    ?>
                                </div>

                                <div class="w-100"></div> <!-- Fuerza salto de línea -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Imagen actual del producto</label><br>
                                        <?php
                                        $eid = isset($_GET['editid']) ? $_GET['editid'] : '';
                                        $producto = mysqli_query($conn, "SELECT * FROM producto WHERE id_producto='$eid'");
                                        $Imagen = '';
                                        if ($producto && $rowProducto = mysqli_fetch_array($producto)) {
                                            $Imagen = $rowProducto['imagen'];

                                            

                                            // Mostrar la imagen si existe
                                            if (!empty($Imagen)) {
                                                echo '<img src="' . htmlspecialchars($Imagen) . '" alt="Imagen del producto" class="img-fluid mt-2" style="width: 100%; max-height: 200px; object-fit: contain;">';


                                            } else {
                                                echo '<p class="text-muted">No hay imagen disponible.</p>';
                                            }
                                        }
                                    ?>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Vista previa</label><br>
                                        <img id="imagePreview" alt="Vista previa de la imagen seleccionada" style="width: 100%; max-height: 200px; object-fit: contain;">
                                        <br><br>
                                        <label for="imagen" class="form-label">Nueva imagen del Producto</label>
                                        <input type="file" name="imagen" id="imagen" class="form-control" onchange="previewImage(event)" accept="image/*">
                                    </div>

                                </div>


                                <div class="w-100"></div> <!-- Fuerza salto de línea -->

                                <div class="col-md-12">

                                    <div class="col-md-12">
                                        <button type="submit" name="update" class="allButton">Actualizar</button>

                                        <button type="button" class="allButton" onclick="clearCategoryForm()">
                                        Limpiar
                                    </button>

                                    </div>
                                </div>




                            </form>
                        </div>

                    </div>
                </div>
            </div>




        </div>

    </section>
    <script>
    function clearCategoryForm() {
        // Get the category form
        var form = document.getElementById("categoryForm");

        // Reset text inputs and textarea
        var inputs = form.querySelectorAll("input[type=text], textarea");
        inputs.forEach(function(input) {
            input.value = "";
        });
    }
    </script>

    
    <script src="../JS/index.js"></script>
</body>

</html>