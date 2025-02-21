<?php
include '../auth/koneksi.php';

// Periksa apakah ID tersedia di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data
    $query = "DELETE FROM ditujukan WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='ditujukan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.location='ditujukan.php';</script>";
    }
} else {
    echo "<script>alert('ID tidak ditemukan!'); window.location='ditujukan.php';</script>";
}
?>
