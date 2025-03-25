<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ./auth/login.php");
    exit();
}

include './auth/koneksi.php';

// Ambil data dari database dengan pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "
    SELECT penerima.*, ditujukan.nama_penerima, ditujukan.sebutan 
    FROM penerima 
    LEFT JOIN ditujukan ON penerima.ditunjukan = ditujukan.id
    WHERE penerima.berupa LIKE ?
    ORDER BY penerima.hari_tanggal DESC
";


$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$query = $stmt->get_result();

// Hapus data jika ada request delete
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $delete_stmt = $conn->prepare("DELETE FROM penerima WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    $delete_stmt->execute();
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
    <head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <link rel="icon" type="image/x-icon" href="images/icon/favicon.png">
    <link rel="apple-touch-icon" href="images/icon/apple-touch-icon.png">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <div class="flex items-center justify-between bg-white shadow-md p-4 rounded-lg">
        <h2 class="text-2xl font-bold text-gray-700">Tanda Terima</h2>
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600">
            Logout
        </a>
    </div>

    <div class="flex-grow p-6">
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

    <div class="container mx-auto mt-4">
        <div class="border border-gray-300 rounded-lg overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200">
                    <thead class="bg-blue-500 text-white shadow-md">
                        <tr>
                            <th class="py-2 px-4 border">ID</th>
                            <th class="py-2 px-4 border">Berupa</th>
                            <th class="py-2 px-4 border">Ditujukan</th>
                            <th class="py-2 px-4 border">Hari/Tanggal</th>
                            <th class="py-2 px-4 border">Doc Scan</th>  
                            <th class="py-2 px-4 border">File Tanda Terima</th>
                            <th class="py-2 px-4 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($row = $query->fetch_assoc()) : ?>
                        <tr class="border-b hover:bg-gray-100 transition">
                            <td class="py-2 px-4 border text-center"><?= $no++; ?></td>
                            <td class="py-2 px-4 border"><?= nl2br(htmlspecialchars($row['berupa'])); ?></td>
                            <td class="py-2 px-4 border">
                                <?= (!empty($row['sebutan']) ? $row['sebutan'] . " " : "") . htmlspecialchars($row['nama_penerima']); ?>
                            </td>
                            <td class="py-2 px-4 border text-center">
                                <?= date('d-m-Y', strtotime($row['hari_tanggal'])); ?>
                            </td>
                            <td class="py-2 px-4 border text-center">
                                <?php if (!empty($row['doc_scan_path'])): ?>
                                    <a href="<?= $row['doc_scan_path']; ?>" target="_blank" class="text-green-500 underline">Doc Scan</a>
                                <?php else: ?>
                                    <span class="text-gray-500">Tidak ada scan</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-2 px-4 border text-center">
                                <?php if (!empty($row['file_path'])): ?>
                                    <a href="<?= $row['file_path']; ?>" target="_blank" class="text-blue-500 underline">File Tanda Terima</a>
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
                                    <a href="cetak_file.php?id=<?php echo sha1($row['id']); ?>" target="_blank"
                                    class="px-3 py-1 min-w-[80px] text-center bg-blue-500 text-white rounded-md hover:bg-blue-600 shadow-md">
                                        Cetak
                                    </a>        
                                    <button onclick="openModal(<?php echo $row['id']; ?>, <?= !empty($row['file_path']) ? 'true' : 'false'; ?>)" 
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
    </div>

    <!-- Modal Upload -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-bold mb-4">Upload File</h2>
            <form action="upload_proses.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="uploadId">
                
                <!-- Input File Tanda Terima -->
                <div id="fileInputGroup">
                    <label class="block mb-2 text-sm font-medium text-gray-900">File Tanda Terima</label>
                    <input type="file" name="file_tanda_terima" id="fileTandaTerima" class="block w-full border p-2 rounded-md">
                </div>

                <!-- Input Doc Scan -->
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Doc Scan</label>
                    <input type="file" name="doc_scan" class="block w-full border p-2 rounded-md">
                </div>

                <div class="mt-4 flex justify-between">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer class="text-center mt-4 text-gray-600 text-sm py-4 border-t bg-gray-100 shadow-md">
        <p class="text-sm font-semibold"><i class="fas fa-copyright"></i> 2025 Dibuat oleh <span class="text-blue-500">Ahmad Rifal</span> - SMK Jakarta Pusat 1 - Div. Komersial TI</p>
        <a href="changelog.php" class="font-semibold hover:text-blue-700 transition">Tanda Terima <span class="text-blue-500 font-semibold underline hover:text-blue-700 transition">APP v1.3.3</span></a>
    </footer>

    <script>
    function openModal(id, fileExists) {    
        document.getElementById('uploadId').value = id;

        if (fileExists) {
            document.getElementById('fileInputGroup').classList.add('hidden');
            document.getElementById('fileTandaTerima').removeAttribute('required');
        } else {
            document.getElementById('fileInputGroup').classList.remove('hidden');
            document.getElementById('fileTandaTerima').setAttribute('required', 'true');
        }

        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }
</script>

</body>
</html>