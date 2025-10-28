<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

/* ====== TAMBAH DATA ====== */
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($conn, "INSERT INTO anggota (nama, nisn, kelas, jurusan)
                         VALUES ('$nama','$nisn','$kelas','$jurusan')");
}

/* ====== UPDATE DATA ====== */
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($conn, "UPDATE anggota SET 
        nama='$nama', nisn='$nisn', kelas='$kelas', jurusan='$jurusan'
        WHERE id='$id'");
}

/* ====== HAPUS DATA ====== */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM anggota WHERE id='$id'");
}

/* ====== AMBIL DATA ====== */
$result = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Anggota</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background: #f1f5fb;
        margin: 0;
        padding: 0;
        color: #333;
    }
    .container {
        width: 90%;
        margin: 30px auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    h2 {
        font-size: 24px;
        font-weight: 600;
        color: #222;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 25px;
    }
    .form-box {
        background: #f8faff;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #dde6ff;
        margin-bottom: 30px;
    }
    .form-box h3 {
        color: #2575fc;
        margin-bottom: 10px;
        font-weight: 600;
    }
    label {
        display: block;
        margin: 8px 0 4px;
        font-weight: 500;
    }
    input, select {
        width: 100%;
        padding: 10px;
        border: 1px solid #cfd8f0;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 14px;
    }
    input:focus, select:focus {
        border-color: #2575fc;
        outline: none;
        box-shadow: 0 0 4px rgba(37,117,252,0.3);
    }
    .btn {
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        color: white;
        background: linear-gradient(135deg, #2575fc, #6a11cb);
        font-weight: 500;
        transition: 0.3s;
    }
    .btn:hover { transform: translateY(-1px); opacity: 0.9; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        border-radius: 10px;
        overflow: hidden;
    }
    th {
        background: #2575fc;
        color: white;
        text-align: left;
        padding: 12px;
    }
    td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    tr:hover { background: #f5f8ff; }
    .edit-btn, .delete-btn {
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        cursor: pointer;
        color: #fff;
        font-size: 13px;
        transition: 0.3s;
    }
    .edit-btn { background: #00a8ff; }
    .delete-btn { background: #ff4757; }
    .edit-btn:hover { background: #0097e6; }
    .delete-btn:hover { background: #e84118; }
    .form-edit {
        background: #eef4ff;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #ccd9ff;
        margin-top: 30px;
    }
</style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <h2>👥 Manajemen Data Anggota</h2>

    <!-- Form Tambah Anggota -->
    <div class="form-box">
        <h3>Tambah Anggota</h3>
        <form method="POST">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>

            <label>NISN</label>
            <input type="text" name="nisn" placeholder="Masukkan NISN" required>

            <label>Kelas</label>
            <select name="kelas" required>
                <option value="">-- Pilih Kelas --</option>
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
            </select>

            <label>Jurusan</label>
            <select name="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="RPL">RPL</option>
                <option value="AKL">AKL</option>
                <option value="BDP">BDP</option>
                <option value="MP">MP</option>
            </select>

            <button type="submit" name="tambah" class="btn">+ Tambah Anggota</button>
        </form>
    </div>

    <!-- Tabel Data Anggota -->
    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NISN</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['nisn']); ?></td>
            <td><?= htmlspecialchars($row['kelas']); ?></td>
            <td><?= htmlspecialchars($row['jurusan']); ?></td>
            <td>
                <button type="button" class="edit-btn" onclick='editForm(<?= json_encode($row); ?>)'>Edit</button>
                <a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">
                    <button type="button" class="delete-btn">Hapus</button>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Form Edit Anggota -->
    <div id="editForm" class="form-edit" style="display:none;">
        <h3>Edit Anggota</h3>
        <form method="POST">
            <input type="hidden" name="id" id="editId">

            <label>Nama</label>
            <input type="text" name="nama" id="editNama" required>

            <label>NISN</label>
            <input type="text" name="nisn" id="editNisn" required>

            <label>Kelas</label>
            <select name="kelas" id="editKelas" required>
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
            </select>

            <label>Jurusan</label>
            <select name="jurusan" id="editJurusan" required>
                <option value="RPL">RPL</option>
                <option value="AKL">AKL</option>
                <option value="BDP">BDP</option>
                <option value="MP">MP</option>
            </select>

            <button type="submit" name="update" class="btn">💾 Simpan Perubahan</button>
        </form>
    </div>

</div>

<script>
function editForm(data) {
    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editId').value = data.id;
    document.getElementById('editNama').value = data.nama;
    document.getElementById('editNisn').value = data.nisn;
    document.getElementById('editKelas').value = data.kelas;
    document.getElementById('editJurusan').value = data.jurusan;
    window.scrollTo({ top: document.getElementById('editForm').offsetTop - 80, behavior: 'smooth' });
}
</script>

</body>
</html>
