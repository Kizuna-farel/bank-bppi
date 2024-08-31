<?php
//instalisasi session
session_start();

// mengecek apakah user ada session user aktif,jika tidak arahkan ke login.php
if (!isset($_SESSION['siswa'])) {
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
    <h3>selamat datang, <?php echo $_SESSION['siswa']['username']; ?></h3>
    <hr>
    <h2>saldo anda, <?php echo $_SESSION['siswa']['saldo']; ?></h2>

    <a href="riwayat.php">transaction</a>
    <a href="#">acara 1</a>
    <a href="#">acara 2</a>
    <a href="#">acara 3</a>
    <a href="chage.php">kelola akun</a>

    <hr>
    <a href="logout.php">logout</a>
</body>

</html>