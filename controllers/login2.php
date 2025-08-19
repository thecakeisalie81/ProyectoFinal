<?php
session_start();
include "../system/config.php";


if (isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $password = validate($_POST['password']);

    if (empty($username)) {
        header("Location: ../login.php?error=User name is required");
        exit();
    } else if (empty($password)) {
        header("Location: ../login.php?error=Password is required");
        exit();
    } else {
        $sql = "SELECT * FROM usuarios WHERE nombre=?";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                // Verify hashed password
                if (password_verify($password, $row['contra'])) {

                        // Set session variables
                        $_SESSION['nombre'] = $row['nombre'];
                        $_SESSION['id_usuario'] = $row['id_usuario'];

                        // Redirect to dashboard or desired location
                        $_SESSION['login']=true;
                        header("Location: ../views/Dashboard.php");
                        
                        exit();
                    }
                }
            }
        }

        // If we reach here, the login failed
        $_SESSION['Incorrect'] = true; // Set successEdit to true
        $_SESSION['IncorrectMessage'] = 'Incorrect Password, please try again!';
        echo "<script>document.location='../login.php';</script>";
    }
?>