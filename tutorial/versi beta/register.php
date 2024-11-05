<?php
session_start();

// Database connection
$servername = "localhost"; // Update if necessary
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirmPassword = $_POST['confirmPassword'];

    // Validate passwords
    if ($_POST['password'] !== $confirmPassword) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username atau email sudah terdaftar!'); window.history.back();</script>";
        exit;
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $email, $username, $password);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt->error; // Tampilkan error
        exit;
    }

    echo "<script>alert('Registrasi berhasil!'); window.location.href = 'form.html';</script>";
    exit;
}

$conn->close();
?>
