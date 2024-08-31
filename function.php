<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "bank") or die('Database tidak terhubung');

// Function to login as admin
function login_admin($conn, $username, $password)
{
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        if (password_verify($password, $data['password'])) {
            return $data;
        }
    }
    return false;
}

// Function to login as student
function login_user($conn, $username, $password)
{
    $query = "SELECT * FROM siswa WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        if (password_verify($password, $data['password'])) {
            return $data;
        }
    }
    return false;
}

// Fungsi untuk menambahkan saldo
function tambahSaldo($namaId, $jumlah, $teler)
{
    global $conn;
    $conn->begin_transaction();

    try {
        $query = "UPDATE siswa SET saldo = saldo + ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $jumlah, $namaId);
        $stmt->execute();

        $query = "INSERT INTO transaksi_riwayat (nama_id, type, amount, timestamp, teler) VALUES (?, 'Tambah Saldo', ?, NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ids", $namaId, $jumlah, $teler);
        $stmt->execute();

        $conn->commit();
        return "Saldo berhasil ditambahkan!";
    } catch (Exception $e) {
        $conn->rollback();
        return "Error menambahkan saldo: " . $e->getMessage();
    }
}

// Fungsi untuk mengurangi saldo
function kurangiSaldo($namaId, $jumlah, $teler)
{
    global $conn;
    $conn->begin_transaction();

    try {
        $query = "SELECT saldo FROM siswa WHERE id = ? FOR UPDATE";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $namaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['saldo'] < $jumlah) {
            throw new Exception("Saldo tidak cukup!");
        }

        $query = "UPDATE siswa SET saldo = saldo - ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $jumlah, $namaId);
        $stmt->execute();

        $query = "INSERT INTO transaksi_riwayat (nama_id, type, amount, timestamp, teler) VALUES (?, 'Kurangi Saldo', ?, NOW(), ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ids", $namaId, $jumlah, $teler);
        $stmt->execute();

        $conn->commit();
        return "Pengurangan saldo berhasil!";
    } catch (Exception $e) {
        $conn->rollback();
        return $e->getMessage();
    }
}

// Fungsi untuk mendapatkan saldo saat ini
function getSaldo($namaId)
{
    global $conn;
    $query = "SELECT saldo FROM siswa WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $namaId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['saldo'];
    }
    return "User tidak ditemukan";
}

// Fungsi untuk mendapatkan riwayat transaksi
function getTransactionHistory($nama)
{
    global $conn;

    $query = "SELECT s.id, s.saldo, tr.timestamp, tr.type, tr.amount, tr.teler 
              FROM siswa s
              LEFT JOIN transaksi_riwayat tr ON s.id = tr.nama_id
              WHERE s.username = ?
              ORDER BY tr.timestamp DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transactions = [];
        $saldo = 0;
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
            $saldo = $row['saldo'];
        }

        echo "<h2>Riwayat Transaksi untuk Nama: " . htmlspecialchars($nama) . "</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Tanggal</th><th>Jenis Transaksi</th><th>Jumlah</th><th>Teler</th></tr>";
        foreach ($transactions as $transaction) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($transaction['timestamp']) . "</td>";
            echo "<td>" . htmlspecialchars($transaction['type']) . "</td>";
            echo "<td>" . htmlspecialchars($transaction['amount']) . "</td>";
            echo "<td>" . htmlspecialchars($transaction['teler']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<h2>Total Saldo: Rp " . number_format($saldo, 2, ',', '.') . "</h2>";
    } else {
        echo "Tidak ada riwayat transaksi atau nama tidak ditemukan";
    }
}

// Fungsi untuk mendaftarkan akun baru
// Fungsi untuk mendaftarkan akun baru
function registerUser($conn, $nama, $username, $password, $nis, $jurusan)
{
    // Cek jika username, nama, atau NIS sudah ada
    $query = "SELECT * FROM siswa WHERE username = ? OR nama = ? OR nis = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $username, $nama, $nis);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errorMessages = [];

        // Periksa setiap kemungkinan kesalahan
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] === $username) {
                $errorMessages[] = "Username sudah terdaftar!";
            }
            if ($row['nama'] === $nama) {
                $errorMessages[] = "Nama sudah terdaftar!";
            }
            if ($row['nis'] === $nis) {
                $errorMessages[] = "NIS sudah terdaftar!";
            }
        }

        return implode(" ", $errorMessages);
    } else {
        // Enkripsi password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Tambahkan data baru ke database
        $query = "INSERT INTO siswa (nama, username, password, nis, jurusan, saldo) VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $nama, $username, $passwordHash, $nis, $jurusan);
        $result = $stmt->execute();

        if ($result) {
            return "Pendaftaran berhasil!";
        } else {
            return "Error mendaftar: " . $conn->error;
        }
    }
}

// Fungsi untuk mendapatkan semua data siswa
function getAllStudents($conn)
{
    $query = "SELECT id, nama, username, password, nis, jurusan, saldo FROM siswa";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        return $students;
    } else {
        return "Tidak ada data siswa";
    }
}

// Fungsi untuk mencari siswa berdasarkan nama atau username
function searchStudents($conn, $searchTerm)
{
    $query = "SELECT id, nama, username, password, nis, jurusan, saldo FROM siswa WHERE nama LIKE ? OR username LIKE ?";
    $searchTerm = "%$searchTerm%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        return $students;
    } else {
        return "Tidak ada data siswa yang ditemukan";
    }
}

// Fungsi untuk mengganti username
function changeUsername($conn, $nama, $newUsername)
{
    $query = "SELECT * FROM siswa WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $newUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username sudah terdaftar!";
    } else {
        $query = "UPDATE siswa SET username = ? WHERE nama = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $newUsername, $nama);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return "Username berhasil diubah!";
        } else {
            return "Error mengubah username: " . $conn->error;
        }
    }
}

// Fungsi untuk mengganti password
function changePassword($conn, $nama, $oldPassword, $newPassword)
{
    $query = "SELECT password FROM siswa WHERE nama = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($oldPassword, $row['password'])) {
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $query = "UPDATE siswa SET password = ? WHERE nama = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $newPasswordHash, $nama);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return "Password berhasil diubah!";
        } else {
            return "Error mengubah password: " . $conn->error;
        }
    } else {
        return "Password lama salah!";
    }
}

// Fungsi untuk mengganti NIS
function changeNIS($conn, $nama, $newNIS)
{
    $query = "SELECT * FROM siswa WHERE nis = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $newNIS);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "NIS sudah terdaftar!";
    } else {
        $query = "UPDATE siswa SET nis = ? WHERE nama = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $newNIS, $nama);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return "NIS berhasil diubah!";
        } else {
            return "Error mengubah NIS: " . $conn->error;
        }
    }
}

// Fungsi untuk mengganti jurusan
function changeJurusan($conn, $nama, $newJurusan)
{
    $query = "UPDATE siswa SET jurusan = ? WHERE nama = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $newJurusan, $nama);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return "Jurusan berhasil diubah!";
    } else {
        return "Error mengubah jurusan: " . $conn->error;
    }
}

// Fungsi untuk mengganti username dan password admin
function changeAdminUsernameAndPassword($conn, $name, $oldPassword, $newUsername, $newPassword)
{
    // Mengecek apakah username baru sudah terdaftar
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $newUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "Username baru sudah terdaftar!";
    }

    // Mengecek apakah password lama benar
    $query = "SELECT username, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($oldPassword, $row['password'])) {
        $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

        // Memperbarui username dan password
        $query = "UPDATE admin SET username = ?, password = ? WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $newUsername, $newPasswordHash, $name);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return "Username dan password berhasil diubah!";
        } else {
            return "Error mengubah username dan password: " . $conn->error;
        }
    } else {
        return "Password saat ini salah!";
    }
}





// Fungsi untuk mendaftar admin
// function register_admin($conn, $nama, $username, $password)
// {
//     // Hash password
//     $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//     // Query untuk memasukkan data admin
//     $query = "INSERT INTO admin (nama, username, password) VALUES (?, ?, ?)";
//     if ($stmt = $conn->prepare($query)) {
//         $stmt->bind_param("sss", $nama, $username, $hashed_password);
//         if ($stmt->execute()) {
//             return true;
//         } else {
//             // Handle query execution error
//             echo "Error executing query: " . $stmt->error;
//         }
//         $stmt->close();
//     } else {
//         // Handle error preparing the statement
//         echo "Error preparing statement: " . $conn->error;
//     }
//     return false;
// }



// Validasi input
function validateInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?>