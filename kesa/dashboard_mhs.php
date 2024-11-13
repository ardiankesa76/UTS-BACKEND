<?php
session_start();
include('config.php');

// Logout functionality
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Hancurkan session
    header("Location: index.php"); // Redirect ke halaman login
    exit();
}

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Ambil data pengguna yang login dari database
$username = $_SESSION['username'];
$query = "SELECT id, nama, username, nilai FROM users WHERE username = ?";
$stmt = mysqli_prepare($sambung, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard_mhs.css"> <!-- Link ke file CSS -->
</head>
<body>
    <div class="dashboard-container">
        <h2>Dashboard Mahasiswa</h2>
        
        <table>
            <thead>
                <tr>
                    <th>No Daftar</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['nama']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['nilai']); ?></td>
                </tr>
            </tbody>
        </table>
        <a href="dashboard_mhs.php?action=logout" class="logout-button">Logout</a> <!-- Tombol Logout -->
    </div>
</body>
</html>
