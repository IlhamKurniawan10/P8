<?php
session_start(); // Mulai sesi

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.html");
    exit();
}

include 'koneksi.php'; // Koneksi database

$sql = "SELECT id, nama_kost, harga, jumlah_kamar, gambar_path FROM jogjakost ORDER BY tanggal_unggah DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard awal.css">
    <title>Dashboard User</title>
</head>
<body>
    <header>
        <div class="logo">Jogja Kost</div>
        <div class="search-container">
            <input type="text" placeholder="Cari Lokasi">
            <button>Cari</button>
            <a href="filter.html" class="filter-button">Filter</a>
        </div>
        <nav>
            <a href="hubungi kami.html">Hubungi Kami</a>
            <a href="user.html">User</a>
        </nav>
    </header>

    <main>
        <div class="kost-container">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $status_kamar = ($row["jumlah_kamar"] > 0) ? "sisa " . $row["jumlah_kamar"] . " kamar" : "Habis";
                    $remaining_class = ($row["jumlah_kamar"] > 0) ? "" : "remaining";

                    echo '<div class="kost-card">';
                    echo '<img src="' . htmlspecialchars($row["gambar_path"]) . '" alt="' . htmlspecialchars($row["nama_kost"]) . '" class="kost-image">';
                    echo '<div class="kost-info">';
                    echo '<p>' . htmlspecialchars($row["nama_kost"]) . '</p>';
                    echo '<p>Rp. ' . number_format($row["harga"], 0, ',', '.') . '/bulan</p>';
                    echo '<p class="' . $remaining_class . '">' . $status_kamar . '</p>';
                    echo '<a href="detail_kost.php?id=' . urlencode($row["id"]) . '" class="detail-button">Lihat Detail</a>';
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
$conn->close();
?>
