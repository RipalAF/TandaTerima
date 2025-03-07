<?php
include './auth/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Ambil data dari database
    $sql = "SELECT file_path FROM penerima WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $file_path = $row['file_path']; // Simpan data lama

    // Jika file tanda terima sudah ada, hanya boleh upload Doc Scan
    if (!empty($file_path)) {
        if (!empty($_FILES['doc_scan']['name'])) {
            $doc_scan = $_FILES['doc_scan']['name'];
            $doc_scan_path = "uploads/" . basename($doc_scan);
            move_uploaded_file($_FILES['doc_scan']['tmp_name'], $doc_scan_path);

            // Update hanya untuk Doc Scan
            $sql = "UPDATE penerima SET doc_scan_path = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $doc_scan_path, $id);
            $stmt->execute();
        }
    } else {
        // Jika belum ada file, bisa upload dua-duanya
        if (!empty($_FILES['file_tanda_terima']['name'])) {
            $file_tanda_terima = $_FILES['file_tanda_terima']['name'];
            $file_path = "uploads/" . basename($file_tanda_terima);
            move_uploaded_file($_FILES['file_tanda_terima']['tmp_name'], $file_path);
        }

        if (!empty($_FILES['doc_scan']['name'])) {
            $doc_scan = $_FILES['doc_scan']['name'];
            $doc_scan_path = "uploads/" . basename($doc_scan);
            move_uploaded_file($_FILES['doc_scan']['tmp_name'], $doc_scan_path);
        }

        // Update database
        $sql = "UPDATE penerima SET file_path = ?, doc_scan_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $file_path, $doc_scan_path, $id);
        $stmt->execute();
    }

    header("Location: index.php");
    exit();
}
?>
