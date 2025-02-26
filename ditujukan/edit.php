<?php
include '../auth/koneksi.php';

$id = $_GET['id'] ?? '';

$query = "SELECT * FROM ditujukan WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sebutan = $_POST['sebutan'];
    $nama_penerima = $_POST['nama_penerima'];
    $divisi = $_POST['divisi'];
    $nama_perusahaan = $_POST['nama_perusahaan'];

    $updateQuery = "UPDATE ditujukan SET sebutan=?, nama_penerima=?, divisi=?, nama_perusahaan=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $sebutan, $nama_penerima, $divisi, $nama_perusahaan, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='ditujukan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ditujukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="../images/icon/favicon.png">
</head>
<body class="min-h-screen bg-gray-200 flex flex-col items-center p-6">
    <h2 class="text-3xl font-bold text-gray-700 mb-6">Edit Data Ditujukan</h2>

    <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-lg">
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Sebutan:</label>
                <input type="text" name="sebutan" value="<?php echo htmlspecialchars($data['sebutan']); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Nama Penerima:</label>
                <input type="text" name="nama_penerima" value="<?php echo htmlspecialchars($data['nama_penerima']); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Divisi:</label>
                <input type="text" name="divisi" value="<?php echo htmlspecialchars($data['divisi']); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Nama Perusahaan:</label>
                <input type="text" name="nama_perusahaan" value="<?php echo htmlspecialchars($data['nama_perusahaan']); ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="history.back()" class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600">
                    Batal
                </button>
            </div>
        </form>
    </div>
</body>
</html>
