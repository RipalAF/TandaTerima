<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include './auth/koneksi.php';

// Ambil data dari database dengan pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = $conn->query("
    SELECT penerima.*, ditujukan.nama_penerima AS nama_ditujukan 
    FROM penerima 
    LEFT JOIN ditujukan ON penerima.id = ditujukan.id
    WHERE penerima.berupa LIKE '%$search%' OR penerima.penerima LIKE '%$search%'
");


// Hapus data jika ada request delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM penerima WHERE id=$id");
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima APP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/icon/favicon.png">
    <link rel="apple-touch-icon" href="images/icon/apple-touch-icon.png">
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-between bg-white shadow-md p-4 rounded-lg">
        <h2 class="text-2xl font-bold text-gray-700">Tanda Terima</h2>
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600">
            Logout
        </a>
    </div>

    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-2 sm:space-y-0">
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <a href="tambah.php" class="w-full sm:w-auto bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 text-center">
                    Tambah Surat
                </a>
                <a href="./ditujukan/ditujukan.php" class="w-full sm:w-auto bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 text-center">
                    List Ditujukan
                </a>
            </div>
            <form method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" 
                    placeholder="Cari surat..." 
                    class="px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <div class="border border-gray-300 rounded-lg overflow-hidden">
        <div class="max-h-[510px] overflow-y-auto">
            <table class="w-full bg-white border border-gray-200">
            <thead class="bg-blue-500 text-white sticky top-0 z-10">
    <tr>
        <th class="py-2 px-4 border">ID</th>
        <th class="py-2 px-4 border">Berupa</th>
        <th class="py-2 px-4 border">Ditunjukan</th>
        <th class="py-2 px-4 border">Hari/Tanggal</th>
        <th class="py-2 px-4 border">Penerima</th>
        <th class="py-2 px-4 border">File</th> <!-- Tambahkan kolom File -->
        <th class="py-2 px-4 border">Aksi</th>
    </tr>
</thead>
<tbody>
    <?php 
    $no = 1;
    while ($row = $query->fetch_assoc()) : ?>
    <tr class="border-b hover:bg-gray-100 transition">
        <td class="py-2 px-4 border text-center"><?php echo $no++; ?></td>
        <td class="py-2 px-4 border"><?php echo nl2br($row['berupa']); ?></td>
        <td class="py-2 px-4 border"><?php echo $row['nama_ditujukan']; ?></td>
        <td class="py-2 px-4 border text-center"><?php echo date('d-m-Y', strtotime($row['hari_tanggal'])); ?></td>
        <td class="py-2 px-4 border"><?php echo $row['penerima']; ?></td>
        <td class="py-2 px-4 border text-center">
            <?php if (!empty($row['file_path'])): ?>
                <a href="<?php echo $row['file_path']; ?>" target="_blank" class="text-blue-500 underline">
                    File Tanda Terima
                </a>
            <?php else: ?>
                <span class="text-gray-500">Tidak ada file</span>
            <?php endif; ?>
        </td>
        <td class="py-2 px-4 border text-center">
            <div class="flex flex-wrap justify-center gap-2 sm:flex-nowrap">
                <a href="edit.php?id=<?php echo $row['id']; ?>" 
                class="px-3 py-1 min-w-[80px] text-center bg-yellow-500 text-white rounded-md hover:bg-yellow-600 shadow-md">
                    Edit
                </a>
                <a href="?hapus=<?php echo $row['id']; ?>" 
                onclick="return confirm('Yakin ingin menghapus?');"
                class="px-3 py-1 min-w-[80px] text-center bg-red-500 text-white rounded-md hover:bg-red-600 shadow-md">
                    Hapus
                </a>
            </div>
            <div class="mt-2 flex justify-center gap-2">
                <a href="cetak_file.php?id=<?php echo $row['id']; ?>" target="_blank"
                class="px-3 py-1 min-w-[80px] text-center bg-blue-500 text-white rounded-md hover:bg-blue-600 shadow-md">
                    Cetak
                </a>
                <button onclick="openModal(<?php echo $row['id']; ?>)" 
                class="px-3 py-1 min-w-[80px] text-center bg-green-500 text-white rounded-md hover:bg-green-600 shadow-md">
                    Upload
                </button>
            </div>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>
</table>
        </div>
    </div>
    <!-- Modal Upload -->
    <div id="uploadModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Upload File</h2>
            <form action="upload_proses.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="uploadId">
                <input type="file" name="file" required class="block w-full border p-2 rounded-md">
                <div class="flex justify-end mt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded-lg mr-2">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {    
            document.getElementById('uploadId').value = id;
            document.getElementById('uploadModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }
    </script>
</body>
</html>