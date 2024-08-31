<?php
session_start();

require "function.php";

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result_admin = login_admin($conn, $username, $password);
    if ($result_admin !== false) {
        $_SESSION['admin'] = $result_admin;
        echo '<script>alert("Selamat datang, ' . $result_admin['nama'] . '");
         location.href="index.php";</script>';
    } else {
        echo '<script>alert("username atau password salah");</script>';
    }

    $result_user = login_user($conn, $username, $password);
    if ($result_user !== false) {
        $_SESSION['siswa'] = $result_user;
        echo '<script>alert("Selamat datang, ' . $result_user['nama'] . '");
         location.href="index.php";</script>';
    } else {
        echo '<script>alert("username atau password salah");</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var checkbox = document.getElementById("show-password");
            if (checkbox.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</head>
<body>

    <form id="login-form" method="post">
        <h2>Login siswa</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <input type="checkbox" id="show-password" onclick="togglePasswordVisibility()"> Show password
        <br><br>
        <button type="submit">login</button>
    </form>
</body>
</html>
