<?php
// pastikan session sudah aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <div class="nav-left">
        <img src="logo.png" alt="Logo Unhan" class="logo">
        <span class="brand">Perpustakaan Digital</span>
    </div>

    <div class="menu">
        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="data_buku.php" class="<?= basename($_SERVER['PHP_SELF']) == 'data_buku.php' ? 'active' : '' ?>">Data Buku</a>
        <a href="data_anggota.php" class="<?= basename($_SERVER['PHP_SELF']) == 'data_anggota.php' ? 'active' : '' ?>">Data Anggota</a>
        <a href="peminjaman.php" class="<?= basename($_SERVER['PHP_SELF']) == 'peminjaman.php' ? 'active' : '' ?>">Peminjaman</a>
        <a href="pengembalian.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pengembalian.php' ? 'active' : '' ?>">Pengembalian</a>
    </div>

    <div class="user">
        <span class="welcome">👋 <?php echo $_SESSION['nama']; ?></span>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</nav>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    nav {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 40px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        position: sticky;
        top: 0;
        z-index: 1000;
        backdrop-filter: blur(10px);
    }

    .nav-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.6);
        transition: transform 0.3s ease;
    }

    .logo:hover {
        transform: rotate(10deg) scale(1.05);
    }

    .brand {
        font-size: 20px;
        font-weight: 600;
        color: #fff;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }

    .menu {
        display: flex;
        gap: 25px;
    }

    .menu a {
        color: #f1f1f1;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        position: relative;
        padding: 8px 10px;
        border-radius: 6px;
        transition: color 0.3s ease;
    }

    .menu a::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 0;
        height: 2px;
        background: #fff;
        transition: width 0.3s ease;
    }

    .menu a:hover::after,
    .menu a.active::after {
        width: 100%;
    }

    .menu a.active {
        color: #fff;
        font-weight: 600;
    }

    .user {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .welcome {
        color: #fff;
        font-weight: 500;
        font-size: 15px;
    }

    .logout {
        background: #e63946;
        padding: 8px 14px;
        color: #fff;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .logout:hover {
        background: #c92a3f;
        transform: scale(1.05);
    }
</style>
