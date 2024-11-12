<?php
require_once '../config.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $nama_program = $_POST['nama_program'];
            $deskripsi = $_POST['deskripsi'];
            $durasi = $_POST['durasi'];
            $pelatih_id = $_POST['pelatih_id'];
            $stmt = $pdo->prepare("INSERT INTO program_pelatihan (nama_program, deskripsi, durasi, pelatih_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama_program, $deskripsi, $durasi, $pelatih_id]);
            $_SESSION['success'] = 'Program berhasil ditambahkan';
            break;
            
        case 'edit':
            $id = $_POST['id'];
            $nama_program = $_POST['nama_program'];
            $deskripsi = $_POST['deskripsi']; 
            $durasi = $_POST['durasi'];
            $pelatih_id = $_POST['pelatih_id'];
            $stmt = $pdo->prepare("UPDATE program_pelatihan SET nama_program=?, deskripsi=?, durasi=?, pelatih_id=? WHERE id=?");
            $stmt->execute([$nama_program, $deskripsi, $durasi, $pelatih_id, $id]);
            $_SESSION['success'] = 'Program berhasil diupdate';
            break;
            
        case 'delete':
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM program_pelatihan WHERE id=?");
            $stmt->execute([$id]);
            $_SESSION['success'] = 'Program berhasil dihapus';
            break;
    }
    header('Location: manage_programs.php');
    exit();
}

$programs = $pdo->query("SELECT * FROM program_pelatihan")->fetchAll();
$pelatih = $pdo->query("SELECT * FROM pelatih")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Program Pelatihan</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../component/navbar.php'; ?>
    
    <div class="container main-content">
        <h1 class="page-title">Kelola Program Pelatihan</h1>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success-alert">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <button class="btn add-btn" onclick="showAddModal()">
            Tambah Program Baru
        </button>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Program</th>
                        <th>Deskripsi</th>
                        <th>Durasi</th>
                        <th>Pelatih</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($programs as $program): ?>
                        <tr>
                            <td><?= htmlspecialchars($program['nama_program']) ?></td>
                            <td class="description-cell"><?= htmlspecialchars($program['deskripsi']) ?></td>
                            <td><?= htmlspecialchars($program['durasi']) ?></td>
                            <td>
                                <?php
                                $trainer = array_filter($pelatih, function($t) use ($program) {
                                    return $t['id'] == $program['pelatih_id'];
                                });
                                $trainer = reset($trainer);
                                echo htmlspecialchars($trainer['nama_pelatih'] ?? 'Tidak tersedia');
                                ?>
                            </td>
                            <td class="action-cell">
                                <button class="btn edit-btn" onclick="editProgram(<?= $program['id'] ?>)">Edit</button>
                                <form class="delete-form" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $program['id'] ?>">
                                    <button type="submit" class="btn delete-btn">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Program -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Tambah Program Baru</h2>
            <form method="POST" class="program-form">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="nama_program">Nama Program</label>
                    <input type="text" id="nama_program" name="nama_program" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label for="durasi">Durasi</label>
                    <input type="text" id="durasi" name="durasi" required>
                </div>
                <div class="form-group">
                    <label for="pelatih_id">Pelatih</label>
                    <select id="pelatih_id" name="pelatih_id" required>
                        <?php foreach($pelatih as $p): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_pelatih']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn submit-btn">Simpan</button>
                    <button type="button" class="btn cancel-btn" onclick="hideModal('addModal')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editProgram(id) {
            // Implementasi fungsi edit
            console.log('Edit program dengan ID:', id);
        }
    </script>
</body>
</html>