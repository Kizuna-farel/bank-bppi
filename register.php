<?php
session_start();
require "function.php";

if (!isset($_SESSION['admin'])) {
    header('location:login.php');//arahkan ke login.php
}

// Cek jika form telah disubmit
if (isset($_POST['register'])) {
    // Ambil dan bersihkan input dari form
    $nama = validateInput($_POST['nama']);
    $username = validateInput($_POST['username']);
    $password = validateInput($_POST['password']);
    $nis = validateInput($_POST['nis']);
    $jurusan = validateInput($_POST['jurusan']);

    // Validasi input
    if (!empty($nama) && !empty($username) && !empty($password) && !empty($nis) && !empty($jurusan)) {
        $message = registerUser($conn, $nama, $username, $password, $nis, $jurusan);
    } else {
        $message = "Semua field harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun</title>
</head>

<body>
    <h1>Registrasi Akun Baru</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>
        <br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="nis">NIS:</label>
        <input type="text" id="nis" name="nis" required>
        <br>
        <label for="jurusan">Jurusan:</label>
        <input type="text" id="jurusan" name="jurusan" required>
        <br>
        <input type="submit" name="register" value="Daftar">
    </form>

    <?php
    if (isset($message)) {
        echo "<p>$message</p>";
    }
    ?>
<a href="admin.php">back</a>
</body>

</html>

<?php
$conn->close();
?>