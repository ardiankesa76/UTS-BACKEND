<?php
session_start();
include('config.php');

// Logout functionality
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Hancurkan session
    header("Location: index.php"); // Redirect ke halaman login
    exit();
}

// Proses penghapusan data jika username dikirim melalui URL
if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Menghapus data berdasarkan username
    $query = "DELETE FROM users WHERE username = ?";
    $stmt = mysqli_prepare($sambung, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);

    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, redirect kembali ke dashboard
        header("Location: dashboard_admin.php"); // Redirect ke halaman ini setelah penghapusan
        exit();
    } else {
        echo "Gagal menghapus data.";
    }
}

// Proses tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($nama) && !empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (nama, username, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($sambung, $query);
        mysqli_stmt_bind_param($stmt, "sss", $nama, $username, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            echo "Pengguna berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan pengguna.";
        }
    } else {
        echo "Semua field harus diisi.";
    }
}

// Ambil semua data dari tabel users
$query = "SELECT id, nama, username FROM users"; // Ambil id, nama, dan username
$result = mysqli_query($sambung, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard_admin.css"> <!-- Link ke file CSS -->
</head>
<body>
    <div class="dashboard-container">
        <h2>Daftar Pengguna</h2>

        <!-- Form Tambah Data -->
        <form class="add-user-form" action="" method="POST">
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Tambah</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>No Daftar</th>
                    <th>Nama</th> <!-- Kolom untuk Nama -->
                    <th>Username</th>
                    <th>Aksi</th> <!-- Kolom untuk aksi (hapus) -->
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama']) . "</td>"; // Menampilkan Nama
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>
                                <a href='dashboard_admin.php?username=" . urlencode($row['username']) . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");' class='delete-button'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data</td></tr>"; // Update colspan
                }
                ?>
            </tbody>
        </table>
        <a href="dashboard_admin.php?action=logout" class="logout-button">Logout</a> <!-- Tombol Logout -->
    </div>
</body>
</html>
