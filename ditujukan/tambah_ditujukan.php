<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include '../auth/koneksi.php';

// Proses tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sebutan = $_POST['sebutan'];
    $nama_penerima = $_POST['nama_penerima'];
    $divisi = $_POST['divisi'];
    $nama_perusahaan = $_POST['nama_perusahaan'];

    $stmt = $conn->prepare("INSERT INTO ditujukan (sebutan, nama_penerima, divisi, nama_perusahaan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $sebutan, $nama_penerima, $divisi, $nama_perusahaan);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='ditujukan.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ditujukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-2xl mt-20">
        <h2 class="text-2xl font-bold mb-4 text-gray-700">Tambah Ditujukan</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Sebutan</label>
                <select name="sebutan" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
                    <option value="Bapak">Bapak</option>
                    <option value="Ibu">Ibu</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Nama Penerima</label>
                <input type="text" name="nama_penerima" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Divisi</label>
                <input type="text" name="divisi" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="flex justify-between">
                <a href="ditujukan.php" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Kembali</a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
