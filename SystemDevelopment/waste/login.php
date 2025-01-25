<?php
    session_start();
    include('db_connection.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['login_username'];
        $password = $_POST['login_password'];

        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = "Username and password are required.";
            header("Location: login.php");
            exit();
        }

        // Use a prepared statement to avoid SQL injection
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($connection, $query);
        
        if ($result && mysqli_num_rows($result) == 1)  {
            $user_data = mysqli_fetch_assoc($result);
            $hashed_password = $user_data['password'];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Set session variables and redirect
                $_SESSION['user_id'] = $user_data['user_id'];
                $_SESSION['username'] = $user_data['username'];
                $_SESSION['email'] = $user_data['email'];

                // Set success message
                $_SESSION['login_success'] = "Login successful.";

                // Redirect to the homepage
                header("Location: homepage.php");
                exit();
            } else {
                // Incorrect password
                $_SESSION['login_error'] = "Incorrect password.";
                header("Location: login.php");
                exit();
            }
        } else {
            // User not found
            $_SESSION['login_error'] = "Incorrect Username.";
            header("Location: login.php");
            exit();
        }
    }

    mysqli_close($connection);
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/login.css">
  
</head>
<body>



    <div class="login-container">
        <h1>Waste Wisdom</h1>
        <h2>Login</h2>
        <form class="login-form" action="" method="POST">
            <div class="form-group">
                <label for="login_username">Username:</label>
                <input type="text" id="login_username" name="login_username" required>
            </div>
            <div class="form-group">
                <label for="login_password">Password:</label>
                <input type="password" id="login_password" name="login_password" required>
                <span class="toggle-password" id="togglePassword">Show Password</span>
            </div>
            <div class="form-group center-button">
                <button type="submit">Login</button>
                
            </div>
        </form>
        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="error-message"><?php echo $_SESSION['login_error']; ?></div>
        <?php endif; ?>

        <?php if(isset($_SESSION['login_success'])): ?>
        <div class="success-message"><?php echo $_SESSION['login_success']; ?></div>
        <?php endif; ?>

        <div class="register-link">
            <p>If you don't have an account, <a href="register.php">register here</a>.</p>
        </div>
        
    </div>

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