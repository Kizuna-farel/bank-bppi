<?php
//instalisasi session
session_start();

// mengecek apakah user ada session user aktif,jika tidak arahkan ke login.php
if (!isset($_SESSION['admin'])) {
    header('location:login.php');//arahkan ke login.php
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>halaman admin</h1>
    <a href="logout.php">logout</a>
    <hr>
    <h3>selamat datang, <?php echo $_SESSION['admin']['nama']; ?></h3>

    <a href="saldo.php">ubah saldo</a>
    <a href="register.php">register siswa</a>
    <a href="data.php">data</a>
    <a href="change_admin.php">kelola akun admin</a>
</body>

</html>