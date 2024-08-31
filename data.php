<?php
session_start();
require "function.php";

// Proses pencarian jika ada
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchTerm = validateInput($_POST['searchTerm']);
    $searchResults = searchStudents($conn, $searchTerm);
} else {
    // Ambil semua siswa jika tidak ada pencarian
    $searchResults = getAllStudents($conn);
}

if (!isset($_SESSION['admin'])) {
    header('location:login.php');//arahkan ke login.php
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
</head>

<body>
    <h1>Data Siswa</h1>

    <!-- Form Pencarian -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="searchTerm">Cari Siswa:</label>
        <input type="text" id="searchTerm" name="searchTerm">
        <input type="submit" name="search" value="Cari">
    </form>

    <!-- Tabel Data Siswa -->
    <h2>Daftar Siswa</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Username</th>
            <!-- <th>Password</th> -->
            <th>NIS</th>
            <th>Jurusan</th>
            <th>Saldo</th>
        </tr>
        <?php
        if (is_array($searchResults)) {
            foreach ($searchResults as $student) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($student['id']) . "</td>";
                echo "<td>" . htmlspecialchars($student['nama']) . "</td>";
                echo "<td>" . htmlspecialchars($student['username']) . "</td>";
                // echo "<td>" . htmlspecialchars($student['password']) . "</td>";
                echo "<td>" . htmlspecialchars($student['nis']) . "</td>";
                echo "<td>" . htmlspecialchars($student['jurusan']) . "</td>";
                echo "<td>" . number_format(htmlspecialchars($student['saldo']), 2, ',', '.') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>" . htmlspecialchars($searchResults) . "</td></tr>";
        }
        ?>
    </table>

    <a href="admin.php">back</a>
</body>

</html>
