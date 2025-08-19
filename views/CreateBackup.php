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
    <title>Backups</title>
</head>

<body>
    <?php 
        ob_start(); // ← Añade esto al inicio

        include '../includes/sidebar_admin.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['descargar_backup'])) {
        Descarga();
    }
    

    function Descarga(){
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = ""; // Si tienes contraseña, colócala aquí
    $db_name = "auto_todo";

    $fecha = date("Ymd-His");
    $salida_sql = $db_name.'_'.$fecha.'.sql';

    // Ruta completa a mysqldump
    $dump_path = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

    // Comando corregido
    $command = "\"$dump_path\" -h $db_host -u $db_user " . ($db_pass !== "" ? "-p$db_pass" : "") . " $db_name > \"$salida_sql\"";

    // Ejecutar el comando
    system($command, $output);

    // Verificar si el archivo se creó y tiene contenido
    if (!file_exists($salida_sql) || filesize($salida_sql) === 0) {
        echo "Error: El archivo SQL no se generó correctamente.";
        return;
    }

    // Crear ZIP
    $zip = new ZipArchive();
    $Salida_zip = $db_name.'_'.$fecha.'.zip';

    if ($zip->open($Salida_zip, ZipArchive::CREATE) === true) {
    $zip->addFile($salida_sql, basename($salida_sql));
    $zip->close();
    unlink($salida_sql); // Elimina el .sql

    // Verifica que el ZIP existe y tiene contenido
    if (!file_exists($Salida_zip) || filesize($Salida_zip) === 0) {
        die("Error: ZIP no generado correctamente.");
    }

    // Limpia cualquier salida previa
    if (ob_get_length()) ob_end_clean();

    // Enviar encabezados
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . basename($Salida_zip) . '"');
    header('Content-Length: ' . filesize($Salida_zip));
    header('Pragma: public');
    header('Cache-Control: must-revalidate');

    // Enviar el archivo
    $fp = fopen($Salida_zip, 'rb');
    fpassthru($fp);
    fclose($fp);

    // Eliminar después de enviar
    unlink($Salida_zip);
    exit;
}
 else {
        echo "Error al crear el archivo ZIP.";
    }
}

    if (isset($_POST['restaurar_backup'])) {
    if ($_FILES['backup']['error'] === UPLOAD_ERR_OK) {
        $archivoTmp = $_FILES['backup']['tmp_name'];
        $nombreArchivo = $_FILES['backup']['name'];

        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        if (strtolower($extension) !== 'sql') {
            echo "<script>alert('El archivo debe tener extensión .sql');</script>";
        } else {
            $db_host = "localhost";
            $db_user = "root";
            $db_pass = ""; // Si tienes contraseña, colócala aquí
            $db_name = "auto_todo";

            // Usar mysql CLI para restaurar más rápido
            $mysql_path = "C:\\xampp\\mysql\\bin\\mysql.exe";
            $cmd = "\"$mysql_path\" -h $db_host -u $db_user " . ($db_pass !== "" ? "-p$db_pass" : "") . " $db_name < \"$archivoTmp\"";

            system($cmd, $output);

            echo "<script>alert('Backup restaurado con éxito ✅');</script>";
        }
    } else {
        echo "<script>alert('Error al subir el archivo');</script>";
    }
}

    ob_end_flush();
    ?>

    <section class="dashboard">
        <div class="top">
            <ion-icon class="navToggle" name="menu-outline"></ion-icon>
        </div>

        <!-- Modal Cargar Backup -->
        <div class="modal fade" id="modalCargarBackup" tabindex="-1" aria-labelledby="modalCargarBackupLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCargarBackupLabel">Cargar Backup de Base de Datos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <label for="backup" class="form-label">Selecciona archivo .sql:</label>
                            <input type="file" class="form-control" name="backup" accept=".sql" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" name="restaurar_backup" class="btn btn-primary">Cargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-offset-2">

                        <div class="box">

                            <h2>
                                <ion-icon ion-icon name="calendar-outline"></ion-icon> Backups de base de datos
                            </h2>


                            <h4>Backups</h4>
                            <p>Descargue y cargue backups de la base de datos.</p>
                            <div class="accountbox">
                                <div class="settingTitle">
                                    <p><strong>Descargar backup</strong> </p>
                                </div>

                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-8">
                                        <p>Descarga backup de la base de datos con los datos actuales.</p>
                                    </div>
                                    <div class="col-12 col-md-4 text-md-end text-center">
                                        <form method="post">
                                            <button type="submit" name="descargar_backup"
                                                class="btn btn-primary w-100 w-md-auto"
                                                style="background-color:#4F46E5; border-color:#4F46E5;">
                                                <b>Descargar</b>
                                            </button>
                                        </form>
                                    </div>
                                </div>



                                <div class="settingTitle">
                                    <p><strong>Cargar backup</strong></p>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-12 col-md-8">
                                        <p>Cargue backup de la base de datos para devolverla a un estado anterior.</p>
                                    </div>
                                    <div class="col-12 col-md-4 text-md-end text-center">
                                        <button type="button" class="btn btn-primary w-100 w-md-auto" style="background-color:#4F46E5; border-color:#4F46E5;"   
                                            data-bs-toggle="modal" data-bs-target="#modalCargarBackup">
                                            <b>Cargar</b>
                                        </button>
                                    </div>
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