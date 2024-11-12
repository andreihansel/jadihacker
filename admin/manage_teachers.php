<?php
require_once '../config.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['action'])){
        $action = $_POST['action'];
        switch ($action) {
            case 'add':
                $nama_pelatih = $_POST['nama_pelatih'];
                $keahlian = $_POST['keahlian'];
                $email = $_POST['email'];
                $no_telp = $_POST['no_telp'];
                $stmt = $pdo->prepare("INSERT INTO pelatih (nama_pelatih, keahlian, email, no_telp) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nama_pelatih, $keahlian, $email, $no_telp]);
                $_SESSION['success'] = 'Pelatih berhasil ditambahkan';
                break;

            case 'edit':
                $id = $_POST['id'];
                $nama_pelatih = $_POST['nama_pelatih'];
                $keahlian = $_POST['keahlian'];
                $email = $_POST['email'];
                $no_telp = $_POST['no_telp'];
                $stmt = $pdo->prepare("UPDATE pelatih SET nama_pelatih=?, keahlian=?, email=?, no_telp=? WHERE id=?");
                $stmt->execute([$nama_pelatih, $keahlian, $email, $no_telp, $id]);
                $_SESSION['success'] = 'Data pelatih berhasil diupdate';
                break;

            case 'delete':
                $id = $_POST['id'];
                $stmt = $pdo->prepare("DELETE FROM pelatih WHERE id=?");
                $stmt->execute([$id]);
                $_SESSION['success'] = 'Pelatih berhasil dihapus';
                break;
        }
        header('Location: manage_teachers.php');
        exit();
    }
}

$pelatih = $pdo->query("SELECT * FROM pelatih ORDER BY nama_pelatih")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Pelatih</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../component/navbar.php'; ?>
    
    <div class="container">
        <h1 class="page-title">Kelola Data Pelatih</h1>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success-alert">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="action-container">
            <button class="btn add-btn" onclick="showAddModal()">Tambah Pelatih Baru</button>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Pelatih</th>
                        <th>Keahlian</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pelatih as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nama_pelatih']) ?></td>
                            <td><?= htmlspecialchars($p['keahlian']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['no_telp']) ?></td>
                            <td class="action-buttons">
                                <button class="btn edit-btn" onclick="editPelatih(<?= $p['id'] ?>, '<?= htmlspecialchars($p['nama_pelatih']) ?>', '<?= htmlspecialchars($p['keahlian']) ?>', '<?= htmlspecialchars($p['email']) ?>', '<?= htmlspecialchars($p['no_telp']) ?>')">Edit</button>
                                <form method="POST" class="delete-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatih ini?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <button type="submit" class="btn delete-btn">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah Pelatih -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <h2>Tambah Pelatih Baru</h2>
                <form method="POST" class="form-container">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label for="nama_pelatih">Nama Pelatih</label>
                        <input type="text" id="nama_pelatih" name="nama_pelatih" required>
                    </div>
                    <div class="form-group">
                        <label for="keahlian">Keahlian</label>
                        <input type="text" id="keahlian" name="keahlian" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn submit-btn">Simpan</button>
                        <button type="button" class="btn cancel-btn" onclick="hideModal('addModal')">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Pelatih -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <h2>Edit Data Pelatih</h2>
                <form method="POST" class="form-container">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_nama_pelatih">Nama Pelatih</label>
                        <input type="text" id="edit_nama_pelatih" name="nama_pelatih" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_keahlian">Keahlian</label>
                        <input type="text" id="edit_keahlian" name="keahlian" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Email</label>
                        <input type="email" id="edit_email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_telp">No. Telepon</label>
                        <input type="text" id="edit_no_telp" name="no_telp" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn submit-btn">Simpan Perubahan</button>
                        <button type="button" class="btn cancel-btn" onclick="hideModal('editModal')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editPelatih(id, nama, keahlian, email, noTelp) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama_pelatih').value = nama;
            document.getElementById('edit_keahlian').value = keahlian;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_no_telp').value = noTelp;
            document.getElementById('editModal').style.display = 'block';
        }
    </script>
</body>
</html>
