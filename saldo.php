<?php
session_start();


// mengecek apakah user ada session user aktif,jika tidak arahkan ke login.php
if (!isset($_SESSION['admin'])) {
    header('location:login.php');//arahkan ke login.php
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "bank") or die('Database tidak terhubung');

// Menyertakan fungsi-fungsi
require "function.php";

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = validateInput($_POST['username']); // Mengambil username sebagai identifier
    $jumlah = validateInput($_POST['jumlah']);
    $teler = validateInput($_POST['teler']);

    if (!is_numeric($jumlah)) {
        $message = "Input jumlah tidak valid!";
    } else {
        // Ambil ID siswa berdasarkan username
        $query = "SELECT id FROM siswa WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $namaId = $row['id'];

            if (isset($_POST['tambahSaldo'])) {
                $message = tambahSaldo($namaId, $jumlah, $teler);
            } elseif (isset($_POST['kurangiSaldo'])) {
                $message = kurangiSaldo($namaId, $jumlah, $teler);
            }
        } else {
            $message = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Saldo</title>
</head>

<body>
    <form id="add-balance-form" method="post">
        <h2>Tambah/Kurangi Saldo</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br> <!-- Input username -->
        <label for="jumlah">Jumlah:</label>
        <input type="number" id="jumlah" name="jumlah" required><br><br> <!-- Input jumlah -->
        <label for="teler">Teler:</label>
        <input type="text" id="teler" name="teler" required><br><br> <!-- Input teler -->
        <label for="tanggal">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal"><br><br> <!-- Input tanggal -->
        <button type="submit" name="tambahSaldo">Tambah Saldo</button> <!-- Tombol tambah saldo -->
        <button type="submit" name="kurangiSaldo">Kurangi Saldo</button> <!-- Tombol kurangi saldo -->
    </form>

    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <p>
            <a href="riwayat.php">Lihat Riwayat Transaksi</a>
        </p>

        <a href="admin.php">kembali</a>
</body>

</html>
