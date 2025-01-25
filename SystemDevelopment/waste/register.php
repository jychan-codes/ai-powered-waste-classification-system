<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Wisdom - Register</title>
    <link rel="stylesheet" type="text/css" href="css/register.css">
</head>
<body>


<div class="login-container">
        <h1>Waste Wisdom</h1>
        <h2>Register</h2>
        <form class="login-form" action="" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="login_password">Password:</label>
                <input type="password" id="login_password" name="password" required>
                <span class="toggle-password" id="togglePassword">Show Password</span>
            </div>
            <div class="form-group center-button">
                <button type="submit">Register</button>
            </div>
        </form>
        <?php if(isset($_SESSION['registration_error'])): ?>
            <div class="error-message"><?php echo $_SESSION['registration_error']; ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['registration_success'])): ?>
            <div class="success-message">Registration successful! <a href="login.php">Login here</a>.</div>
        <?php endif; ?>
        <div class="login-link">
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>




<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($full_name) || empty($username) || empty($email) ||  empty($password)) {
        $_SESSION['registration_error'] = "All fields are required.";
        header("Location: register.php");
        exit();

    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the 'users' table
        $insert_query = $connection->prepare("INSERT INTO users (full_name, username, email, password) VALUES (?, ?, ?, ?)");
        $insert_query->bind_param("ssss", $full_name, $username, $email, $hashedPassword);

        if ($insert_query->execute()) {
            $_SESSION['registration_success'] = true;
            header("Location: login.php");
            exit();
        } else {
            // Log or display the specific error message
            $error_message = $insert_query->error;
            $_SESSION['registration_error'] = "Registration failed. Error: $error_message";
            header("Location: register.php");
            exit();
        }
    }
}

mysqli_close($connection);
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('login_password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Show Password' : 'Hide Password';
    });
});
    </script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>