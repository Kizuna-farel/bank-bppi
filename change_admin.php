<?php
session_start();
require "function.php";

// Pastikan admin sudah login
if (!isset($_SESSION['admin'])) {
    die("Anda harus login sebagai admin terlebih dahulu.");
}

// Proses perubahan username atau password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $oldPassword = $_POST['old_password'];
    $newUsername = $_POST['new_username'];
    $newPassword = $_POST['new_password'];

    // Mengubah username dan password
    $result = changeAdminUsernameAndPassword($conn, $name, $oldPassword, $newUsername, $newPassword);
    echo '<script>
            alert("' . $result . '");
            if ("' . $result . '" === "Username dan password berhasil diubah!") {
                window.location.href = "admin.php";
            }
          </script>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Username dan Password Admin</title>
</head>
<body>

<h2>Ubah Username dan Password Admin</h2>
<form method="post">
    <label for="name">Nama:</label>
    <input type="text" id="name" name="name" required><br><br>
    
    <label for="new_username">Username Baru:</label>
    <input type="text" id="new_username" name="new_username" required><br><br>
    
    <label for="old_password">Password Lama:</label>
    <input type="password" id="old_password" name="old_password" required><br><br>
    
    <label for="new_password">Password Baru:</label>
    <input type="password" id="new_password" name="new_password" required><br><br>
    
    <button type="submit">Ubah Username dan Password</button>
</form>

<a href="admin.php">back</a>

</body>
</html>
