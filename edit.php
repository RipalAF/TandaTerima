<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include './auth/koneksi.php';

// Periksa apakah ada parameter ID yang dikirimkan
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Ambil data berdasarkan ID dan JOIN dengan tabel ditujukan
$query = $conn->query("SELECT penerima.*, ditujukan.nama_penerima 
                        FROM penerima 
                        JOIN ditujukan ON penerima.ditunjukan = ditujukan.id 
                        WHERE penerima.id=$id");

$data = $query->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}

// Proses update data jika form dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $berupa = str_replace("\n", "<br>", $_POST['berupa']); // Simpan dengan format newline ke <br>
    $ditunjukan = $_POST['ditunjukan'];
    $hari_tanggal = $_POST['hari_tanggal'];
    $penerima = $_POST['penerima'];

    $sql = "UPDATE penerima SET berupa='$berupa', ditunjukan='$ditunjukan', hari_tanggal='$hari_tanggal', penerima='$penerima' WHERE id=$id";
    
    if ($conn->query($sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal mengupdate data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/icon/favicon.png">
    <link rel="apple-touch-icon" href="images/icon/apple-touch-icon.png">
</head>
<body class="bg-gray-200 p-6 flex justify-center">
    <div class="w-full max-w-lg bg-white p-6 rounded-lg shadow-2xl mt-20">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Edit Data</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold">Berupa:</label>
                <textarea name="berupa" rows="4" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"><?= str_replace("<br>", "\n", htmlspecialchars($data['berupa'])); ?></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Ditujukan:</label>
                <input type="text" name="ditunjukan" value="<?= htmlspecialchars($data['nama_penerima']); ?>" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Hari/Tanggal:</label>
                <input type="date" name="hari_tanggal" value="<?= htmlspecialchars($data['hari_tanggal']); ?>" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Penerima:</label>
                <input type="text" name="penerima" value="<?= htmlspecialchars($data['penerima']); ?>" required
                    class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-between">
                <a href="index.php" class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</body>
</html>