<?php
session_start(); // Start the session

// Include the database configuration
include("config.php");

// Initialize variables
$username = '';
$password = '';
$error_message = '';

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if (!empty($username) && !empty($password)) {
        // Check for admin or dosen credentials
        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['username'] = $username; // Store username in session
            header("Location: dashboard_admin.php"); // Redirect to admin dashboard
            exit();
        } elseif ($username === 'dosen' && $password === 'dosen') {
            $_SESSION['username'] = $username; // Store username in session
            header("Location: dashboard_dosen.php"); // Redirect to dosen dashboard
            exit();
        } else {
            // Use prepared statements to check username in the database
            $stmt = $sambung->prepare("SELECT password, nama FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashed_password, $nama);
                $stmt->fetch();

                // Check the password
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['username'] = $username; // Store username in session
                    $_SESSION['nama'] = $nama; // Optionally store name in session
                    header("Location: dashboard_mhs.php"); // Redirect to mahasiswa dashboard
                    exit();
                } else {
                    $error_message = "Username atau password salah.";
                }
            } else {
                $error_message = "Username atau password salah.";
            }

            $stmt->close();
        }
    } else {
        $error_message = "Username dan password harus diisi.";
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
    <title>Login Form</title>
    <link rel="stylesheet" href="css/login.css">
    <style>
        .error-message {
            background-color: #f44336; /* Latar belakang merah */
            color: white; /* Teks berwarna putih */
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px; /* Jarak atas */
            text-align: center; /* Teks di tengah */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" placeholder="Masukan username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Masukan password">
            </div>
            <button class="btn">Login</button>
            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <div class="forgot-password">
                <a href="#">Lupa Password?</a>
            </div>
            <div class="signup">
                <p>Belum punya akun? <a href="register.php">Register Disini</a></p>
            </div>
        </form>
    </div>
</body>
</html>
