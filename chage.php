<?php
require 'function.php';

$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = validateInput($_POST['nama']);
    $newUsername = validateInput($_POST['new_username']);
    $oldPassword = validateInput($_POST['old_password']);
    $newPassword = validateInput($_POST['new_password']);
    $newNIS = validateInput($_POST['new_nis']);
    $newJurusan = validateInput($_POST['new_jurusan']);

    if (!empty($newUsername)) {
        $result = changeUsername($conn, $nama, $newUsername);
        if ($result !== "Username berhasil diubah!") {
            $errorMessages[] = $result;
        }
    }

    if (!empty($oldPassword) && !empty($newPassword)) {
        $result = changePassword($conn, $nama, $oldPassword, $newPassword);
        if ($result !== "Password berhasil diubah!") {
            $errorMessages[] = $result;
        }
    }

    if (!empty($newNIS)) {
        $result = changeNIS($conn, $nama, $newNIS);
        if ($result !== "NIS berhasil diubah!") {
            $errorMessages[] = $result;
        }
    }

    if (!empty($newJurusan)) {
        $result = changeJurusan($conn, $nama, $newJurusan);
        if ($result !== "Jurusan berhasil diubah!") {
            $errorMessages[] = $result;
        }
    }

    if (empty($errorMessages)) {
        echo "<script>alert('Data berhasil diubah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Siswa</title>
    <style>
        .error-message {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <h2>Formulir Perubahan Data Siswa</h2>

        <?php if (!empty($errorMessages)): ?>
                <div class="error-message">
                    <?php foreach ($errorMessages as $message): ?>
                            <p><?php echo $message; ?></p>
                    <?php endforeach; ?>
                </div>
        <?php endif; ?>

        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" required><br><br>

        <label for="new_username">Username Baru:</label>
        <input type="text" name="new_username" id="new_username"><br><br>

        <label for="old_password">Password Lama:</label>
        <input type="password" name="old_password" id="old_password"><br><br>

        <label for="new_password">Password Baru:</label>
        <input type="password" name="new_password" id="new_password"><br><br>

        <label for="new_nis">NIS Baru:</label>
        <input type="text" name="new_nis" id="new_nis"><br><br>

        <label for="new_jurusan">Jurusan Baru:</label>
        <input type="text" name="new_jurusan" id="new_jurusan"><br><br>

        <input type="submit" name="submit" value="Ubah">
    </form>

    <a href="index.php">back</a>
</body>
</html>
