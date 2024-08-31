<?php
session_start();
require "function.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
</head>

<body>
    <h1>Riwayat Transaksi</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" required>
        <input type="submit" name="getTransactionHistory" value="Lihat Riwayat Transaksi">
    </form>

    <?php
    if (isset($_POST['getTransactionHistory'])) {
        $nama = validateInput($_POST['nama']);

        // Validasi input jika diperlukan
        if (!empty($nama)) {
            getTransactionHistory($nama);
        } else {
            echo "Input tidak valid!";
        }
    }
    ?>
<a href="index.php">user</a>
<a href="admin.php">admin</a>
</body>

</html>

<?php
$conn->close();
?>