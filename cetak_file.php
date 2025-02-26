<?php
include './auth/koneksi.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit();
}

$hash_id = $_GET['id'];

// Cari ID asli berdasarkan hash
$query = "SELECT id FROM penerima";
$result = $conn->query($query);

$id_asli = null;
while ($row = $result->fetch_assoc()) {
    if (sha1($row['id']) === $hash_id) {
        $id_asli = $row['id'];
        break;
    }
}

// Jika tidak ditemukan, tampilkan error
if ($id_asli === null) {
    die("Data tidak ditemukan!");
}

// Query untuk mengambil data berdasarkan ID asli
$query = $conn->prepare("
    SELECT penerima.*, ditujukan.sebutan, ditujukan.nama_penerima 
    FROM penerima 
    LEFT JOIN ditujukan ON penerima.ditunjukan = ditujukan.id
    WHERE penerima.id = ?
");
$query->bind_param("i", $id_asli);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Query berhasil dijalankan, tapi tidak ada hasil. ID: $id_asli");
}
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan!";
    exit();
}

setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id');

function formatTanggalIndonesia($tanggal) {
    $date = new DateTime($tanggal);
    $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
    return $formatter->format($date);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tanda Terima Surat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/icon/favicon.png">
    <link rel="apple-touch-icon" href="images/icon/apple-touch-icon.png">
<style>
  @media print {
    .print-text {
        white-space: pre-line; 
    }

    .print-footer {
      font-size: 10px !important;
    }
  }
</style>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body class="p-10 bg-gray-100">
    <div class="bg-white p-10 shadow-lg rounded-lg w-full mx-auto border border-black">
        <div class="flex justify-between items-center mb-3">
            <img src="images/akhlak-logo.png" alt="Logo Kiri" class="h-24">
            <img src="images/pid-logo.png" alt="Logo Kanan" class="h-24">
        </div>
        
        <div class="mt-4 p-4 rounded-lg">
            <div class="flex items-center">
                <p class="text-sm font-semibold w-40">Telah Terima dari</p>
                <p class="w-4 text-left text-sm">:</p>
                <p class="text-sm flex-1">PT. PELITA INDONESIA DJAYA</p>
            </div>
            <div class="flex items-center mt-2">
                <p class="text-sm font-semibold w-40">Berupa</p>
                <p class="w-4 text-left text-sm">:</p>
                <p class="text-sm flex-1 break-words whitespace-normal print-text">
                    <?php echo nl2br(htmlspecialchars($data['berupa'])); ?>
                </p>
            </div>
            <div class="flex items-center mt-2">
                <p class="text-sm font-semibold w-40">Ditujukan kepada</p>
                <p class="w-4 text-left text-sm">:</p>
                <p class="text-sm flex-1">
                    <?php 
                        if (!empty($data['sebutan']) && !empty($data['nama_penerima'])) {
                            echo htmlspecialchars($data['sebutan']) . ' ' . htmlspecialchars($data['nama_penerima']);
                        } else {
                            echo 'Data tidak ditemukan';
                        }
                    ?>
                </p>
            </div>
            <div class="flex items-center mt-2">
                <p class="text-sm font-semibold w-40">Hari/Tanggal</p>
                <p class="w-4 text-left text-sm">:</p>
                <p class="text-sm flex-1">
                    <?php echo formatTanggalIndonesia($data['hari_tanggal']); ?>
                </p>
            </div>
        </div>

        <div class="mt-5 flex justify-end">
            <div class="text-center">
                <p class="mb-28">Penerima,</p>
                <hr class="w-48 border-t-2 border-gray-500">
                <p class="mt-2">
                    <?php 
                        echo !empty($data['penerima']) 
                            ? htmlspecialchars($data['penerima']) 
                            : '<span>(</span><span class="mx-24"></span><span>)</span>'; 
                    ?>
                </p>
            </div>
        </div>
        <p class="text-center text-sm mt-4 border-t pt-4 border-black print-footer">
            Gedung Pelni Kemayoran - Jakarta Pusat | Phone: (021) 42883720 - 42883749 | 
            Email: corporate@pidc.co.id / pt.pidc@gmail.com | Website: pelniservices.com
        </p>
    </div>
</body>
</html>