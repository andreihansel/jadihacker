<?php
require_once '../config.php';

if (!isLoggedIn() || $_SESSION['role'] !== 'peserta') {
    header('Location: ../login.php');
    exit();
}

// Fetch peserta data
$stmt = $pdo->prepare("
    SELECT p.*, u.username 
    FROM peserta p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$peserta = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            UPDATE peserta 
            SET nama_lengkap = ?, email = ?, no_telp = ?, alamat = ?
            WHERE user_id = ?
        ");
        $stmt->execute([
            $_POST['nama_lengkap'],
            $_POST['email'],
            $_POST['no_telp'],
            $_POST['alamat'],
            $_SESSION['user_id']
        ]);
        $success_message = "Profile updated successfully!";
        
        // Refresh peserta data
        $stmt = $pdo->prepare("SELECT * FROM peserta WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $peserta = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Peserta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .profile-container {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .error {
            background: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
    </style>
</head>
<body>
    <?php include '../component/navbar.php'; ?>
    <div class="profile-container">
        <h1>Profile Peserta</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" value="<?php echo htmlspecialchars($peserta['username']); ?>" disabled>
            </div>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($peserta['nama_lengkap']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($peserta['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="no_telp">No. Telepon</label>
                <input type="text" id="no_telp" name="no_telp" value="<?php echo htmlspecialchars($peserta['no_telp']); ?>" required>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($peserta['alamat']); ?></textarea>
            </div>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>