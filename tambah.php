<?php
include './auth/koneksi.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $berupa = htmlspecialchars(trim($_POST['berupa']));
    $ditunjukan = htmlspecialchars(trim($_POST['ditunjukan']));
    $hari_tanggal = htmlspecialchars(trim($_POST['hari_tanggal']));    

    // Validasi di server
    if (empty($berupa)) {
        $errors[] = "Berupa tidak boleh kosong!";
    }
    if (empty($ditunjukan)) {
        $errors[] = "Ditujukan harus dipilih!";
    }
    if (empty($hari_tanggal)) {
        $errors[] = "Hari/Tanggal harus diisi!";
    }

    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        $query = "INSERT INTO penerima (berupa, ditunjukan, hari_tanggal) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $berupa, $ditunjukan, $hari_tanggal);
        $stmt->execute();
        header("Location: index.php");
        exit();
    }
}

// Ambil data dari tabel "ditujukan"
$query_ditujukan = "SELECT * FROM ditujukan";
$result_ditujukan = $conn->query($query_ditujukan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/icon/favicon.png">
    <link rel="apple-touch-icon" href="images/icon/apple-touch-icon.png">
    <script>
        function validateForm(event) {
            let berupa = document.forms["formTambah"]["berupa"].value.trim();
            let ditunjukan = document.forms["formTambah"]["ditunjukan"].value;
            let hari_tanggal = document.forms["formTambah"]["hari_tanggal"].value;

            if (berupa === "" || ditunjukan === "" || hari_tanggal === "") {
                alert("Semua field harus diisi!");
                event.preventDefault();
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gray-200 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white p-4 md:p-6 rounded-lg shadow-2xl">
        <h2 class="text-xl md:text-2xl font-bold text-gray-700 mb-4 text-center">Tambah Data</h2>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                <?php foreach ($errors as $error) echo "<p>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <form name="formTambah" method="POST" class="space-y-4" onsubmit="validateForm(event)">
            <div>
                <label class="block text-gray-700 font-semibold">Berupa:</label>
                <textarea name="berupa" class="w-full border p-2 rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Ditujukan:</label>
                <select name="ditunjukan" class="w-full border p-2 rounded-md focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Ditujukan --</option>
                    <?php while ($row = $result_ditujukan->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['sebutan'] . ' ' . $row['nama_penerima'] . ' - ' . $row['divisi'] . ' (' . $row['nama_perusahaan'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Hari/Tanggal:</label>
                <input type="date" name="hari_tanggal" class="w-full border p-2 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
 
            <div class="flex flex-col md:flex-row justify-between gap-2">
                <a href="index.php" class="w-full md:w-auto text-center px-4 py-2 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600">
                    Kembali
                </a>
                <button type="submit" name="submit" class="w-full md:w-auto px-4 py-2 bg-green-500 text-white rounded-md shadow-md hover:bg-green-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</body>
</html>