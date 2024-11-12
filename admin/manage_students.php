<!-- Previous PHP code remains unchanged until the HTML part -->

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Peserta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include '../component/navbar.php'; ?>

    <div class="container">
        <h1 class="page-title">Manajemen Peserta</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="table-header">Username</th>
                        <th class="table-header">Nama Lengkap</th>
                        <th class="table-header">Email</th>
                        <th class="table-header">No. Telepon</th>
                        <th class="table-header">Alamat</th>
                        <th class="table-header">Program yang Diikuti</th>
                        <th class="table-header">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peserta as $p): ?>
                        <tr class="table-row">
                            <td class="table-cell"><?php echo htmlspecialchars($p['username']); ?></td>
                            <td class="table-cell"><?php echo htmlspecialchars($p['nama_lengkap']); ?></td>
                            <td class="table-cell"><?php echo htmlspecialchars($p['email']); ?></td>
                            <td class="table-cell"><?php echo htmlspecialchars($p['no_telp']); ?></td>
                            <td class="table-cell"><?php echo htmlspecialchars($p['alamat']); ?></td>
                            <td class="table-cell"><?php echo htmlspecialchars($p['program_yang_diikuti']); ?></td>
                            <td class="table-cell action-cell">
                                <button class="btn btn-edit" onclick="editPeserta(<?php echo $p['id']; ?>)">Edit</button>
                                <form method="POST" class="delete-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="btn btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function editPeserta(id) {
            // Implementasi edit dialog bisa ditambahkan di sini
            alert('Edit peserta dengan ID: ' + id);
        }
    </script>
</body>
</html>
