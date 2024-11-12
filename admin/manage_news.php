<?php
require_once '../config.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}

//Handle Form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $judul = $_POST['judul'];
            $konten = $_POST['konten'];
            $stmt = $pdo->prepare("INSERT INTO berita (judul, konten) VALUES (?, ?)");
            $stmt->execute([$judul, $konten]);
            $_SESSION['success'] = 'Berita berhasil ditambahkan';
            break;
            
        case 'edit':
            $id = $_POST['id'];
            $judul = $_POST['judul'];
            $konten = $_POST['konten'];
            $stmt = $pdo->prepare("UPDATE berita SET judul=?, konten=? WHERE id=?");
            $stmt->execute([$judul, $konten, $id]);
            $_SESSION['success'] = 'Berita berhasil diupdate';
            break;
            
        case 'delete':
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM berita WHERE id=?");
            $stmt->execute([$id]);
            $_SESSION['success'] = 'Berita berhasil dihapus';
            break;
    }
    header('Location: manage_news.php');
    exit();
}

$berita = $pdo->query("SELECT * FROM berita ORDER BY tanggal_post DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Berita</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../component/navbar.php'; ?>
    <div class="container">
        <h2>Kelola Berita</h2>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- Form Tambah Berita -->
        <div class="form-section">
            <h3>Tambah Berita Baru</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" required>
                </div>
                <div class="form-group">
                    <label>Konten</label>
                    <textarea name="konten" rows="5" required></textarea>
                </div>
                <button type="submit">Tambah Berita</button>
            </form>
        </div>

        <!-- Tabel Daftar Berita -->
        <div class="table-section">
            <h3>Daftar Berita</h3>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal Post</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($berita as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['judul']) ?></td>
                            <td><?= htmlspecialchars($item['tanggal_post']) ?></td>
                            <td>
                                <button onclick="showEditForm(<?= $item['id'] ?>, '<?= htmlspecialchars($item['judul']) ?>', '<?= htmlspecialchars($item['konten']) ?>')" class="btn-edit">Edit</button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus berita ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Form Edit (Hidden by default) -->
        <div id="editForm" style="display: none;" class="modal">
            <div class="modal-content">
                <h3>Edit Berita</h3>
                <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="judul" id="edit_judul" required>
                    </div>
                    <div class="form-group">
                        <label>Konten</label>
                        <textarea name="konten" id="edit_konten" rows="5" required></textarea>
                    </div>
                    <button type="submit">Simpan Perubahan</button>
                    <button type="button" onclick="hideEditForm()">Batal</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showEditForm(id, judul, konten) {
            document.getElementById('editForm').style.display = 'block';
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_judul').value = judul;
            document.getElementById('edit_konten').value = konten;
        }

        function hideEditForm() {
            document.getElementById('editForm').style.display = 'none';
        }
    </script>
</body>
</html>