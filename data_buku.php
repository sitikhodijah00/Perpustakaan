<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

/* ========== TAMBAH DATA ========== */
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun = $_POST['tahun_terbit'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah_buku'];

    mysqli_query($conn, "INSERT INTO buku (judul, penulis, tahun_terbit, kategori, jumlah_buku)
                         VALUES ('$judul','$penulis','$tahun','$kategori','$jumlah')");
    $msg = "✅ Data buku berhasil ditambahkan.";
}

/* ========== EDIT DATA ========== */
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $tahun = $_POST['tahun_terbit'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah_buku'];

    mysqli_query($conn, "UPDATE buku SET 
        judul='$judul', penulis='$penulis', tahun_terbit='$tahun', 
        kategori='$kategori', jumlah_buku='$jumlah'
        WHERE id='$id'");
    $msg = "✏️ Data buku berhasil diperbarui.";
}

/* ========== HAPUS DATA ========== */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM buku WHERE id='$id'");
    $msg = "🗑️ Data buku berhasil dihapus.";
}

/* ========== AMBIL DATA ========== */
$result = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Buku</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f2f6fc;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 90%;
        margin: 40px auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    h2 {
        margin-bottom: 10px;
        color: #2a2a2a;
        font-size: 24px;
        font-weight: 600;
    }

    .msg {
        background: #e8f0ff;
        padding: 12px 18px;
        border-radius: 8px;
        border-left: 6px solid #2575fc;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .form-box {
        background: #f9fbff;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #dce3f3;
        margin-bottom: 25px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .form-box h3 {
        color: #2575fc;
        margin-bottom: 15px;
        font-weight: 600;
    }

    label {
        display: block;
        margin: 10px 0 5px;
        font-weight: 500;
        color: #333;
    }

    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccd3e0;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 15px;
        transition: border 0.3s ease;
    }

    input:focus {
        border-color: #2575fc;
        outline: none;
    }

    .btn {
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        color: white;
        background: #2575fc;
        font-weight: 600;
        font-size: 15px;
        transition: background 0.3s ease, transform 0.2s;
    }

    .btn:hover {
        background: #1a5fe6;
        transform: scale(1.02);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    th, td {
        padding: 12px 10px;
        border-bottom: 1px solid #e0e6f0;
        text-align: left;
    }

    th {
        background: #2575fc;
        color: #fff;
        font-weight: 600;
    }

    tr:hover {
        background: #f4f7ff;
    }

    .edit-btn, .delete-btn {
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        color: #fff;
        font-size: 14px;
        transition: 0.3s;
    }

    .edit-btn { background: #00a8ff; }
    .delete-btn { background: #ff4757; }

    .edit-btn:hover { background: #0097e6; transform: scale(1.05); }
    .delete-btn:hover { background: #e84118; transform: scale(1.05); }

    .form-edit {
        background: #f1f5ff;
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #cfd8f0;
        margin-top: 30px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    .form-edit h3 {
        color: #2575fc;
        margin-bottom: 15px;
    }

</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>📘 Manajemen Data Buku</h2>

    <?php if (isset($msg)): ?>
        <div class="msg"><?= $msg; ?></div>
    <?php endif; ?>

    <!-- Form Tambah Buku -->
    <div class="form-box">
        <h3>Tambah Buku</h3>
        <form method="POST">
            <label>Judul Buku</label>
            <input type="text" name="judul" placeholder="Masukkan judul buku" required>
            <label>Penulis</label>
            <input type="text" name="penulis" placeholder="Masukkan nama penulis" required>
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" placeholder="Contoh: 2024" required>
            <label>Kategori</label>
            <input type="text" name="kategori" placeholder="Contoh: Novel, Pelajaran, Teknologi" required>
            <label>Jumlah Buku</label>
            <input type="number" name="jumlah_buku" placeholder="Masukkan jumlah buku tersedia" required>
            <button type="submit" name="tambah" class="btn">💾 Simpan Buku</button>
        </form>
    </div>

    <!-- Tabel Data Buku -->
    <h3 style="color:#2575fc;">📚 Daftar Buku</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Aksi</th>
        </tr>

        <?php 
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['judul']); ?></td>
            <td><?= htmlspecialchars($row['penulis']); ?></td>
            <td><?= htmlspecialchars($row['tahun_terbit']); ?></td>
            <td><?= htmlspecialchars($row['kategori']); ?></td>
            <td><?= htmlspecialchars($row['jumlah_buku']); ?></td>
            <td>
                <button type="button" class="edit-btn" onclick="editForm(<?= htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                <a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                    <button type="button" class="delete-btn">Hapus</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Form Edit Buku -->
    <div id="editForm" class="form-edit" style="display:none;">
        <h3>Edit Buku</h3>
        <form method="POST">
            <input type="hidden" name="id" id="editId">
            <label>Judul Buku</label>
            <input type="text" name="judul" id="editJudul" required>
            <label>Penulis</label>
            <input type="text" name="penulis" id="editPenulis" required>
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" id="editTahun" required>
            <label>Kategori</label>
            <input type="text" name="kategori" id="editKategori" required>
            <label>Jumlah Buku</label>
            <input type="number" name="jumlah_buku" id="editJumlah" required>
            <button type="submit" name="update" class="btn">✅ Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
function editForm(data) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editId').value = data.id;
    document.getElementById('editJudul').value = data.judul;
    document.getElementById('editPenulis').value = data.penulis;
    document.getElementById('editTahun').value = data.tahun_terbit;
    document.getElementById('editKategori').value = data.kategori;
    document.getElementById('editJumlah').value = data.jumlah_buku;
    window.scrollTo({ top: document.getElementById('editForm').offsetTop - 100, behavior: 'smooth' });
}
</script>

</body>
</html>
