<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// ===== AMBIL DATA DARI DATABASE =====
$total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku"))['total'];
$total_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM anggota"))['total'];
$total_pinjam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='Dipinjam'"))['total'];
$total_kembali = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='Dikembalikan'"))['total'];

// ===== 5 PEMINJAMAN TERAKHIR =====
$recent_pinjam = mysqli_query($conn, "
    SELECT a.nama, b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.status
    FROM peminjaman p
    JOIN anggota a ON p.id_anggota = a.id
    JOIN buku b ON p.id_buku = b.id
    ORDER BY p.id DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Perpustakaan</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f7fc;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 90%;
        margin: 30px auto;
    }
    h1 {
        color: #333;
        margin-bottom: 10px;
    }
    .subtitle {
        color: #666;
        margin-bottom: 30px;
    }

    /* ===== CARD INFO ===== */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }
    .card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        text-align: center;
        transition: 0.3s;
    }
    .card:hover {
        transform: translateY(-4px);
    }
    .card h2 {
        margin: 0;
        font-size: 28px;
        color: #2575fc;
    }
    .card p {
        color: #555;
        margin-top: 6px;
        font-weight: 500;
    }

    /* ===== TABEL ===== */
    .table-box {
        margin-top: 40px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        padding: 10px;
        border: 1px solid #e1e4eb;
        text-align: center;
    }
    th {
        background: #2575fc;
        color: #fff;
    }
    tr:nth-child(even) {
        background: #f9f9f9;
    }
    .status {
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 6px;
        color: #fff;
    }
    .Dipinjam { background: #e67e22; }
    .Dikembalikan { background: #2ecc71; }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h1>📊 Dashboard Perpustakaan</h1>
    <p class="subtitle">Selamat datang di sistem informasi perpustakaan. Berikut ringkasan aktivitas terkini.</p>

    <!-- CARD STATISTIK -->
    <div class="cards">
        <div class="card">
            <h2><?= $total_buku; ?></h2>
            <p>Total Buku</p>
        </div>
        <div class="card">
            <h2><?= $total_anggota; ?></h2>
            <p>Total Anggota</p>
        </div>
        <div class="card">
            <h2><?= $total_pinjam; ?></h2>
            <p>Buku Sedang Dipinjam</p>
        </div>
        <div class="card">
            <h2><?= $total_kembali; ?></h2>
            <p>Buku Sudah Dikembalikan</p>
        </div>
    </div>

    <!-- TABEL PEMINJAMAN TERAKHIR -->
    <div class="table-box">
        <h3>🕒 Aktivitas Peminjaman Terbaru</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
            <?php 
            $no = 1;
            if (mysqli_num_rows($recent_pinjam) > 0) {
                while ($row = mysqli_fetch_assoc($recent_pinjam)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['judul']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_kembali']); ?></td>
                        <td><span class="status <?= $row['status']; ?>"><?= $row['status']; ?></span></td>
                    </tr>
                <?php endwhile;
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data peminjaman terbaru</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>
