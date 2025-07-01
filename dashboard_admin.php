<?php
session_start(); // Mulai sesi

// Periksa apakah pengguna sudah login dan apakah perannya adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login/login.html"); // Arahkan ke halaman login
    exit();
}

include 'koneksi.php'; // Sertakan file koneksi database

// Query untuk mengambil semua data kost dari database
$sql = "SELECT id, nama_kost, harga, jumlah_kamar, gambar_path FROM jogjakost ORDER BY tanggal_unggah DESC"; // Mengurutkan yang terbaru dulu
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard awal.css"> <title>Dashboard Admin</title>
</head>
<body>
    <header>
        <div class="logo">Dashboard Admin Jogja Kost</div>
        <div class="search-container">
            <input type="text" placeholder="Cari Lokasi">
            <button>Cari</button>
            <a href="filter.html" class="filter-button">Filter</a>
        </div>
        <nav>
            <a href="tambah_kost.php">Tambah Kost</a>
            <a href="logout.php">Logout</a> </nav>
    </header>

    <main>
        <div class="kost-container">
            <?php
            // Cek apakah ada data yang ditemukan
            if ($result->num_rows > 0) {
                // Looping untuk setiap baris data kost
                while($row = $result->fetch_assoc()) {
                    // Tentukan status kamar (sisa/habis)
                    $status_kamar = ($row["jumlah_kamar"] > 0) ? "sisa " . $row["jumlah_kamar"] . " kamar" : "Habis";
                    $remaining_class = ($row["jumlah_kamar"] > 0) ? "" : "remaining"; // Tambahkan class 'remaining' jika habis

                    echo '<div class="kost-card">';
                    // Pastikan path gambar benar. Jika gambar_path di DB menyimpan 'uploads/nama_gambar.jpg',
                    // maka ini akan bekerja dengan baik.
                    echo '<img src="' . htmlspecialchars($row["gambar_path"]) . '" alt="' . htmlspecialchars($row["nama_kost"]) . '" class="kost-image">';
                    echo '<div class="kost-info">';
                    echo '<p>' . htmlspecialchars($row["nama_kost"]) . '</p>';
                    echo '<p>Rp. ' . number_format($row["harga"], 0, ',', '.') . '/bulan</p>'; // Format harga
                    echo '<p class="' . $remaining_class . '">' . $status_kamar . '</p>';
                    // Anda bisa membuat halaman detail kost yang dinamis juga di sini
                    echo '<a href="detail_kost.php?id=' . urlencode($row["id"]) . '&from=admin" class="detail-button">Lihat Detail</a>';

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p style='text-align: center; width: 100%;'>Belum ada data kost yang tersedia.</p>";
            }
            ?>
        </div>
    </main>
</body>
</html>

<?php
// Tutup koneksi database setelah semua data diambil dan ditampilkan
$conn->close();
?>