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

if (isset($_POST['update'])) {
    $eid = isset($_GET['editid']) ? $_GET['editid'] : '';
    $category_name = $_POST['nombre'];
    $description = $_POST['descripcion'];

    
    $sql = "UPDATE categoria SET nombre='$category_name', descripcion='$description' WHERE id_categoria='$eid'";
    
    if (mysqli_query($conn, $sql)) {
        
        $_SESSION['successEdit'] = true; // Set successEdit to true
        $_SESSION['successEditMessage'] = 'El registro se ha actualizado correctamente.';
        echo "<script>document.location='../views/Category.php';</script>";
    } else {
        echo "<script>alert('Something goes wrong: " . mysqli_error($conn) . "');</script>";
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
                        <a href="../views/Category.php" style="font-size: 24px; text-decoration:none; color:black;">
                            <ion-icon name="arrow-back-outline"></ion-icon>
                        </a>

                        <div class="page-header">
                            <h2>Editar categoria</h2>
                        </div>

                        <div>
                            <form class="row g-3" method="POST" id="categoryForm">
                                <?php
                            $eid = isset($_GET['editid']) ? $_GET['editid'] : '';
                            $sql = mysqli_query($conn, "SELECT * FROM categoria WHERE id_categoria='$eid'");
                            if (!$sql) {
                                // Output the error message
                                die("Error: " . mysqli_error($conn));
                            }
                            while ($row = mysqli_fetch_array($sql)) {
                            ?>


                                <div class="col-md-7">
                                    <label for="category_name" class="form-label">Category Name</label>
                                    <input type="text" name="nombre" value="<?php echo $row['nombre'];?>"
                                        id="category_name" class="form-control" required="" placeholder="">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="descripcion" id="category_description" class="form-control"
                                        required="" placeholder=""
                                        style="height: 100px"><?php echo $row['descripcion'];?></textarea>
                                </div>
                                <?php } ?>
                                <div class="col-md-2">
                                    <button type="submit" name="update" class="allButton">Modificar</button>

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

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="../JS/index.js"></script>
</body>

</html>