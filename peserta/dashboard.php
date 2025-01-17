<?php
include '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT pp.*
    FROM program_pelatihan pp
    JOIN peserta_program ppr ON pp.id = ppr.program_id
    JOIN peserta p ON ppr.peserta_id = p.id
    WHERE p.user_id = ?");

$stmt->execute([$_SESSION['user_id']]);
$result = $stmt->fetchAll();

$all_programs = $conn->query('SELECT * FROM program_pelatihan')->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
<?php include '../component/navbar.php'; ?>

<div class="container">
    <h2>Program Pelatihan Saya</h2>

    <?php if (empty($registered_programs)): ?>
        <p>Anda belum mendaftar program pelatihan apapun</p>

    <?php else: ?>
        <div class="program-list">
            <?php foreach ($registered_programs as $program): ?>
                <div class="program-card">
                    <h3><?php echo htmlspecialchars($program['nama_program']); ?></h3>
                    <p><?php echo htmlspecialchars($program['deskripsi']); ?></p>
                    <p>Durasi: <?php echo htmlspecialchars($program['durasi']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>