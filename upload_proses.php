<?php
session_start();
include './auth/koneksi.php'; // Pastikan koneksi sudah benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // ID penerima dari modal
    $uploadDir = "uploads/"; // Folder penyimpanan file

    // Pastikan folder "uploads" ada, jika tidak buat baru
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES["file"]["name"])) {
        $fileName = basename($_FILES["file"]["name"]);
        $filePath = $uploadDir . time() . "_" . $fileName; // Rename dengan timestamp agar unik

        // Cek apakah file berhasil diupload
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
            // Simpan path file ke database
            $stmt = $conn->prepare("UPDATE penerima SET file_path = ? WHERE id = ?");
            $stmt->bind_param("si", $filePath, $id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "File berhasil diupload!";
            } else {
                $_SESSION['error'] = "Gagal menyimpan ke database!";
            }
        } else {
            $_SESSION['error'] = "Gagal mengupload file!";
        }
    } else {
        $_SESSION['error'] = "Tidak ada file yang dipilih!";
    }
}

// Redirect kembali ke halaman utama setelah upload
header("Location: index.php");
exit();
?>
