<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

/* ====== TAMBAH DATA ====== */
if (isset($_POST['tambah'])) {
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    $cek_stok = mysqli_query($conn, "SELECT jumlah_buku FROM buku WHERE id='$id_buku'");
    $stok = mysqli_fetch_assoc($cek_stok)['jumlah_buku'];

    if ($stok > 0) {
        mysqli_query($conn, "INSERT INTO peminjaman (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status)
                             VALUES ('$id_anggota', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali', 'Dipinjam')");
        mysqli_query($conn, "UPDATE buku SET jumlah_buku = jumlah_buku - 1 WHERE id='$id_buku'");
        $msg = "✅ Peminjaman berhasil, stok buku dikurangi.";
    } else {
        $msg = "❌ Gagal! Stok buku habis.";
    }
}

/* ====== AMBIL DATA ====== */
$peminjaman = mysqli_query($conn, "
    SELECT p.id, a.nama, b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.status 
    FROM peminjaman p 
    JOIN anggota a ON p.id_anggota = a.id
    JOIN buku b ON p.id_buku = b.id
    ORDER BY p.id DESC
");

/* ====== DROPDOWN ====== */
$anggota = mysqli_query($conn, "SELECT * FROM anggota ORDER BY nama ASC");
$buku = mysqli_query($conn, "SELECT * FROM buku ORDER BY judul ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Peminjaman</title>
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
        margin-bottom: 15px;
        font-weight: 600;
    }
    .form-box {
        background: #f8fbff;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #d8e0f3;
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin: 8px 0 4px;
        font-weight: 500;
        color: #333;
    }
    select, input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 10px;
        font-size: 14px;
    }
    .btn {
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        color: white;
        background: #2575fc;
        font-weight: 500;
        transition: 0.3s;
    }
    .btn:hover { background: #1a5fe6; }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
        font-size: 14px;
    }
    th {
        background: linear-gradient(135deg, #2575fc, #6a11cb);
        color: #fff;
    }
    tr:nth-child(even) { background: #f9f9f9; }
    .msg {
        background: #e8f0ff;
        padding: 10px 15px;
        border-radius: 6px;
        border-left: 5px solid #2575fc;
        margin-bottom: 15px;
        font-weight: 500;
    }
    .status-dipinjam { color: #2575fc; font-weight: 600; }
    .status-terlambat { color: #ff3b3b; font-weight: 600; }
    .status-dikembalikan { color: #6a11cb; font-weight: 600; }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>📚 Form Peminjaman Buku</h2>

    <?php if (isset($msg)): ?>
        <div class="msg"><?= $msg; ?></div>
    <?php endif; ?>

    <!-- Form Tambah Peminjaman -->
    <div class="form-box">
        <form method="POST">
            <label>Nama Anggota</label>
            <select name="id_anggota" required>
                <option value="">-- Pilih Anggota --</option>
                <?php while ($a = mysqli_fetch_assoc($anggota)): ?>
                    <option value="<?= $a['id']; ?>"><?= htmlspecialchars($a['nama']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Judul Buku</label>
            <select name="id_buku" required>
                <option value="">-- Pilih Buku --</option>
                <?php while ($b = mysqli_fetch_assoc($buku)): ?>
                    <option value="<?= $b['id']; ?>"><?= htmlspecialchars($b['judul']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>Tanggal Peminjaman</label>
            <input type="date" name="tanggal_pinjam" required>

            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" required>

            <button type="submit" name="tambah" class="btn">Simpan</button>
        </form>
    </div>

    <!-- Tabel Data Peminjaman -->
    <h3>📄 Daftar Peminjaman</h3>
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
        $today = date('Y-m-d');
        if (mysqli_num_rows($peminjaman) > 0) {
            while ($row = mysqli_fetch_assoc($peminjaman)):
                $statusClass = '';
                $statusText = $row['status'];

                // logika warna status
                if ($row['status'] == 'Dipinjam' && $row['tanggal_kembali'] < $today) {
                    $statusClass = 'status-terlambat';
                    $statusText = 'Terlambat';
                } elseif ($row['status'] == 'Dipinjam') {
                    $statusClass = 'status-dipinjam';
                } elseif ($row['status'] == 'Dikembalikan') {
                    $statusClass = 'status-dikembalikan';
                }
        ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= htmlspecialchars($row['judul']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kembali']); ?></td>
                    <td class="<?= $statusClass; ?>"><?= $statusText; ?></td>
                </tr>
        <?php endwhile;
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data peminjaman</td></tr>";
        }
        ?>
    </table>

</div>

</body>
</html>
