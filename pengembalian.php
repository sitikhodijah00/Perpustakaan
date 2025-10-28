<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

/* ========== AMBIL DATA ANGGOTA ========== */
$anggota = mysqli_query($conn, "SELECT * FROM anggota ORDER BY nama ASC");

/* ========== PILIH ANGGOTA UNTUK LIHAT PINJAMAN ========== */
if (isset($_POST['pilih_anggota'])) {
    $id_anggota = $_POST['id_anggota'];
    $peminjaman = mysqli_query($conn, "
        SELECT p.id, b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.status
        FROM peminjaman p
        JOIN buku b ON p.id_buku = b.id
        WHERE p.id_anggota = '$id_anggota' AND p.status = 'Dipinjam'
    ");
}

/* ========== PROSES PENGEMBALIAN ========== */
if (isset($_POST['kembalikan'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];

    $ambil = mysqli_query($conn, "SELECT id_buku FROM peminjaman WHERE id='$id_peminjaman'");
    $id_buku = mysqli_fetch_assoc($ambil)['id_buku'];

    mysqli_query($conn, "UPDATE buku SET jumlah_buku = jumlah_buku + 1 WHERE id='$id_buku'");

    mysqli_query($conn, "UPDATE peminjaman 
                         SET status='Dikembalikan', tanggal_pengembalian='$tanggal_pengembalian'
                         WHERE id='$id_peminjaman'");

    $msg = "📗 Buku berhasil dikembalikan dan stok bertambah 1.";
}

/* ========== RIWAYAT PENGEMBALIAN ========== */
$riwayat = mysqli_query($conn, "
    SELECT a.nama, b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.tanggal_pengembalian
    FROM peminjaman p
    JOIN anggota a ON p.id_anggota = a.id
    JOIN buku b ON p.id_buku = b.id
    WHERE p.status = 'Dikembalikan'
    ORDER BY p.tanggal_pengembalian DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengembalian Buku</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #eef2f9;
        margin: 0;
        padding: 0;
    }
    .container {
        width: 90%;
        margin: 30px auto;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    h2 {
        color: #333;
        margin-bottom: 20px;
        font-weight: 600;
    }
    .form-box {
        background: #f8fbff;
        padding: 20px;
        border: 1px solid #d8e0f3;
        border-radius: 8px;
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #333;
    }
    select, input[type="date"] {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
        font-size: 14px;
    }
    .btn {
        padding: 10px 16px;
        background: #2575fc;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn:hover { background: #1a5fe6; }
    .msg {
        background: #e8f0ff;
        padding: 10px 15px;
        border-radius: 6px;
        border-left: 5px solid #2575fc;
        margin-bottom: 15px;
        font-weight: 500;
    }

    /* === TABEL BUKU DIPINJAM === */
    table.peminjaman {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    table.peminjaman th, table.peminjaman td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    table.peminjaman th {
        background: linear-gradient(135deg, #2575fc, #6a11cb);
        color: #fff;
    }
    table.peminjaman tr:nth-child(even) { background: #f9f9f9; }

    .return-btn {
        background: #2ecc71;
        color: white;
        border: none;
        padding: 7px 12px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }
    .return-btn:hover { background: #27ae60; }

    /* === RIWAYAT === */
    .riwayat-box {
        margin-top: 40px;
        background: #f9fffa;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #d8e8d8;
    }
    .riwayat-box h3 {
        color: #2d662d;
        margin-bottom: 10px;
        font-weight: 600;
    }
    table.riwayat {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 15px;
        overflow: hidden;
        border-radius: 10px;
    }
    table.riwayat th {
        background: #2ecc71;
        color: white;
        padding: 10px;
    }
    table.riwayat td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #e1f1e1;
    }
    table.riwayat tr:nth-child(even) {
        background: #f4fff6;
    }
    table.riwayat tr:hover {
        background: #e8ffec;
    }
    .status-late {
        color: #e74c3c;
        font-weight: bold;
    }
    .status-ok {
        color: #27ae60;
        font-weight: bold;
    }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>🔁 Pengembalian Buku</h2>

    <?php if (isset($msg)): ?>
        <div class="msg"><?= $msg; ?></div>
    <?php endif; ?>

    <!-- PILIH ANGGOTA -->
    <div class="form-box">
        <form method="POST">
            <label>Pilih Anggota</label>
            <select name="id_anggota" required>
                <option value="">-- Pilih Anggota --</option>
                <?php while ($a = mysqli_fetch_assoc($anggota)): ?>
                    <option value="<?= $a['id']; ?>"><?= htmlspecialchars($a['nama']); ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="pilih_anggota" class="btn">Tampilkan Pinjaman</button>
        </form>
    </div>

    <!-- TABEL BUKU YANG SEDANG DIPINJAM -->
    <?php if (isset($peminjaman)): ?>
        <h3 style="color:#333;">📚 Buku yang Sedang Dipinjam</h3>
        <table class="peminjaman">
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Tanggal Pengembalian</th>
                <th>Aksi</th>
            </tr>
            <?php 
            $no = 1;
            if (mysqli_num_rows($peminjaman) > 0) {
                while ($row = mysqli_fetch_assoc($peminjaman)): ?>
                    <tr>
                        <form method="POST">
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['judul']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_kembali']); ?></td>
                            <td>
                                <input type="date" name="tanggal_pengembalian" required>
                                <input type="hidden" name="id_peminjaman" value="<?= $row['id']; ?>">
                            </td>
                            <td>
                                <button type="submit" name="kembalikan" class="return-btn">Kembalikan</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile;
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada buku yang sedang dipinjam</td></tr>";
            } ?>
        </table>
    <?php endif; ?>

    <!-- RIWAYAT PENGEMBALIAN -->
    <div class="riwayat-box">
        <h3>📘 Riwayat Pengembalian Buku</h3>
        <table class="riwayat">
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
            </tr>
            <?php 
            $no = 1;
            if (mysqli_num_rows($riwayat) > 0) {
                while ($r = mysqli_fetch_assoc($riwayat)):
                    $tglKembali = strtotime($r['tanggal_kembali']);
                    $tglPengembalian = strtotime($r['tanggal_pengembalian']);
                    $selisih = floor(($tglPengembalian - $tglKembali) / (60 * 60 * 24));
                    if ($selisih > 0) {
                        $status = "<span class='status-late'>❗ Terlambat {$selisih} hari</span>";
                    } else {
                        $status = "<span class='status-ok'>✅ Tepat waktu</span>";
                    }
            ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($r['nama']); ?></td>
                        <td><?= htmlspecialchars($r['judul']); ?></td>
                        <td><?= htmlspecialchars($r['tanggal_pinjam']); ?></td>
                        <td><?= htmlspecialchars($r['tanggal_kembali']); ?></td>
                        <td><?= htmlspecialchars($r['tanggal_pengembalian']); ?></td>
                        <td><?= $status; ?></td>
                    </tr>
            <?php endwhile;
            } else {
                echo "<tr><td colspan='7' style='text-align:center;'>Belum ada pengembalian buku</td></tr>";
            } ?>
        </table>
    </div>

</div>
</body>
</html>
