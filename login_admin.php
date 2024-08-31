<?php
session_start();
require "function.php";

// Generate a CSRF token and store it in the session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    $result_admin = login_admin($conn, $username, $password);
    if ($result_admin !== false) {
        $_SESSION['admin'] = $result_admin;
        echo '<script>alert("Selamat datang, ' . htmlspecialchars($result_admin['nama']) . '");
         location.href="admin.php";</script>';
    } else {
        echo '<script>alert("Username atau password salah");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var checkbox = document.getElementById("show-password");
            passwordInput.type = checkbox.checked ? "text" : "password";
        }
    </script>
</head>

<body>
    <form id="login-form" method="post">
        <h2>Login Admin</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="checkbox" id="show-password" onclick="togglePasswordVisibility()"> Show password
        <br><br>
        <!-- Add CSRF token field -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Login</button>
    </form>
</body>

</html>