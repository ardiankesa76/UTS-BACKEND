<?php
session_start();
include('config.php');

// Logout functionality
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Hancurkan session
    header("Location: index.php"); // Redirect ke halaman login
    exit();
}

// Proses update nilai
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $username = $_POST['username'];
    $nilai = $_POST['nilai'];

    // Update nilai ke database
    $query = "UPDATE users SET nilai = ? WHERE username = ?";
    $stmt = mysqli_prepare($sambung, $query);
    mysqli_stmt_bind_param($stmt, "is", $nilai, $username);

    if (mysqli_stmt_execute($stmt)) {
        echo "Nilai berhasil diperbarui!";
    } else {
        echo "Gagal memperbarui nilai.";
    }
}

// Ambil semua data dari tabel users
$query = "SELECT id, nama, username, nilai FROM users"; // Ambil id, nama, username, dan nilai
$result = mysqli_query($sambung, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard_dosen.css"> <!-- Link ke file CSS -->
</head>
<body>
    <div class="dashboard-container">
        <h2>Daftar Pengguna</h2>

        <table>
            <thead>
                <tr>
                    <th>No Daftar</th>
                    <th>Nama</th> <!-- Kolom untuk Nama -->
                    <th>Username</th>
                    <th>Nilai</th> <!-- Kolom untuk Nilai -->
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
                                <form action='' method='POST'>
                                    <input type='hidden' name='username' value='" . htmlspecialchars($row['username']) . "'>
                                    <input type='number' name='nilai' value='" . htmlspecialchars($row['nilai']) . "' required>
                                    <button type='submit'>Update</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data</td></tr>"; // Update colspan
                }
                ?>
            </tbody>
        </table>
        <a href="dashboard_dosen.php?action=logout" class="logout-button">Logout</a> <!-- Tombol Logout -->
    </div>
</body>
</html>
