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
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['username'] = $username;
            header("Location: tutorial.html"); // Redirect to tutorial page
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Akun belum terdaftar. Silahkan registrasi terlebih dahulu.'); window.history.back();</script>";
        exit;
    }
}

$conn->close();
?>
