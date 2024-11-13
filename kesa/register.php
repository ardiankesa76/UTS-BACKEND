<?php
// Include the database configuration
include("config.php");

// Initialize variables
$nama = '';
$username = '';
$password = '';
$error_message = '';
$success_message = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $nama = $_POST["nama"] ?? '';
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if (!empty($nama) && !empty($username) && !empty($password)) {
        // Hash the password before saving
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements to insert data
        $stmt = $sambung->prepare("INSERT INTO users (nama, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $username, $hashed_password);

        if ($stmt->execute()) {
            $success_message = "Berhasil menyimpan data!";
        } else {
            $error_message = "Gagal menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Semua field harus diisi.";
    }

    // Close the database connection
    mysqli_close($sambung);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukan nama" value="<?php echo htmlspecialchars($nama); ?>">
            </div>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Masukan username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Masukan password">
            </div>
            <button type="submit" class="btn">Register</button>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                <br>
                <a href="login.php"><button>Kembali ke Login</button></a>
            <?php endif; ?>
            <div class="login-link">
                <p>Sudah punya akun? <a href="index.php">Masuk Disini!</a></p>
            </div>
        </form>
    </div>
</body>
</html>
