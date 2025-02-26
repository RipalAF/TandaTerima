<?php
include '../auth/koneksi.php';

$query = "SELECT * FROM ditujukan ORDER BY id ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ditujukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="../images/icon/favicon.png">
    <link rel="apple-touch-icon" href="images/icon/apple-touch-icon.png">
</head>
<body class="bg-gray-200 flex flex-col items-center p-6">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-700 mb-4 text-center">Daftar Ditujukan</h2>

    <div class="flex flex-col md:flex-row gap-2 mb-4">
        <a href="tambah_ditujukan.php" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 w-full md:w-auto text-center">
            + Tambah Ditujukan
        </a>
        <button onclick="window.location.href='../index.php'" 
                class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600 w-full md:w-auto">
            ← Kembali
        </button>
    </div>

    <!-- Tabel menjadi scrollable di layar kecil -->
    <div class="w-full overflow-x-auto">
        <table class="w-full border border-gray-300 rounded-lg overflow-hidden text-sm md:text-base">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-2 px-4 border">ID</th>
                    <th class="py-2 px-4 border">Sebutan</th>
                    <th class="py-2 px-4 border">Nama Penerima</th>
                    <th class="py-2 px-4 border">Divisi</th>
                    <th class="py-2 px-4 border">Nama Perusahaan</th>
                    <th class="py-2 px-4 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $nomor = 1; // Mulai nomor dari 1
                while ($row = $result->fetch_assoc()): 
                ?>
                <tr class="border-b hover:bg-gray-100 odd:bg-gray-50 even:bg-white transition">
                    <td class="py-2 px-4 border text-center"><?php echo $nomor++; ?></td> <!-- Nomor urut dinamis -->
                    <td class="py-2 px-4 border"><?php echo $row['sebutan']; ?></td>
                    <td class="py-2 px-4 border"><?php echo $row['nama_penerima']; ?></td>
                    <td class="py-2 px-4 border"><?php echo $row['divisi']; ?></td>
                    <td class="py-2 px-4 border"><?php echo $row['nama_perusahaan']; ?></td>
                    <td class="py-2 px-4 border text-center whitespace-nowrap">
                        <div class="flex justify-center space-x-2">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="px-3 py-1 bg-yellow-500 text-white rounded-md shadow-md hover:bg-yellow-600">
                                Edit
                            </a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="px-3 py-1 bg-red-500 text-white rounded-md shadow-md hover:bg-red-600" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
