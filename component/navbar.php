<?php
// Ensure session is available
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="top-menu">
    <ul>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <!-- Admin Navigation -->
            <li><a href="/jadihacker/admin/dashboard.php">Admin Dashboard</a></li>
            <li class="dropdown">
                <a href="#">Manage Data</a>
                <div class="dropdown-content">
                    <a href="/jadihacker/admin/manage_students.php">Data Peserta</a>
                    <a href="/jadihacker/admin/manage_teachers.php">Data Pelatih</a>
                    <a href="/jadihacker/admin/manage_programs.php">Program Pelatihan</a>
                    <a href="/jadihacker/admin/manage_news.php">Berita</a>
                </div>
            </li>   
        <?php else: ?>
            <!-- Peserta Navigation -->
            <li><a href="/jadihacker/peserta/dashboard.php">Dashboard</a></li>
            <li><a href="/jadihacker/peserta/pilih_program.php">Pilih Program</a></li>
            <li><a href="/jadihacker/peserta/profile.php">Profile</a></li>
        <?php endif; ?>
        <li><a href="/jadihacker/logout.php">Logout</a></li>
    </ul>
</nav>