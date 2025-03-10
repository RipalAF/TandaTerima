<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username dan password
    $query = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $query->bind_param("ss", $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="../images/icon/favicon.png">
    <link rel="apple-touch-icon" href="../images/icon/apple-touch-icon.png">
</head>
<body class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-blue-400 to-blue-500 p-4 overflow-hidden">

    <!-- Elemen dekoratif -->
    <div class="absolute top-10 left-10 w-40 h-40 bg-white/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-10 right-10 w-52 h-52 bg-white/20 rounded-full blur-3xl animate-pulse delay-200"></div>

    <!-- Logo di pojok kanan atas -->
    <div class="absolute top-4 left-4">
        <img src="../images/pid-logo.png" alt="Logo" class="h-16">
    </div>

    <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg w-full max-w-sm transform transition duration-500 hover:scale-105">
        <h2 class="text-xl md:text-2xl font-bold text-center text-gray-700">Login</h2>

        <?php if (isset($error)) echo "<p class='text-red-500 text-sm text-center mt-2'>$error</p>"; ?>

        <form method="POST" class="mt-4 space-y-4">
            <div>
                <label class="block text-gray-600 text-sm font-semibold">Username:</label>
                <input type="text" name="username" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 outline-none">
            </div>
            <div>
                <label class="block text-gray-600 text-sm font-semibold">Password:</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300 outline-none">
            </div>
            <button type="submit" name="login" class="w-full bg-blue-500 text-white font-semibold py-2 rounded-lg hover:bg-blue-600 transition duration-300">
                Login
            </button>
        </form>
    </div>
</body>
</html> 