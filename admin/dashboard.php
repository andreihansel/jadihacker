<?php
require_once '../config.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->prepare('
    SELECT p.nama_lengkap, pp.nama_program, pp.tanggal_daftar FROM peserta p
    JOIN peserta_program ppr ON p.id = ppr.peserta_id
    JOIN program_PELATIHAN pp ON ppr.program_id = pp.id
    ORDER BY pp.tanggal_daftar DESC');

$registrations = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../component/navbar.php'; ?>
    <div class="container">
        <h2>Daftar Peserta Program Pelatihan</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Program Pelatihan</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registrations as $registration): ?>
                    <tr>
                        <td><?= htmlspecialchars($registration['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($registration['nama_program']) ?></td>
                        <td><?= htmlspecialchars($registration['tanggal_daftar']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
