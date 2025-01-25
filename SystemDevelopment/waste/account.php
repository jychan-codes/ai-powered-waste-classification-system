<?php
session_start();
include('db_connection.php');

if (isset($_POST['logout'])) {
    // Clear all session variables
    session_unset();
    session_destroy();
    header('Location: homepage.php'); // Redirect to the homepage after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Wisdom - Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/account.css"> <!-- Custom CSS for the account page -->
</head>

<body style="background: rgba(255, 255, 255, 0.8) url('icons/background3.png') center center fixed; background-size: cover; color: #fff;">



<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #203E32">
    <a class="navbar-brand" href="homepage.php" style="color: #F2F3EE; font-weight: bold; font-size: 1.5rem;">Waste Wisdom</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="waste.php" style="color: #F2F3EE;">Identify Your Waste</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="nearme.php" style="color: #F2F3EE;">Locate</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php" style="color: #F2F3EE;">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="account.php" style="color: #F2F3EE;">Account</a>
            </li>
            <?php
                if (isset($_SESSION['username'])) {
                    // User is logged in, display "Account" and "Logout"
                    echo '<li class="nav-item"><form method="post"><button type="submit" name="logout" class="btn btn-link" style="color: white;"><img src="icons/logout.png" alt="Logout" style="width: 20px; height: 20px; margin-right: 5px;">Logout</button></form></li>';
                } else {
                    // User is not logged in, display "Login"
                    echo '<li class="nav-item"><a class="nav-link" href="login.php" style="color: #F2F3EE;">Login</a></li>';
                }
            ?>
        </ul>
    </div>
</nav>

<div class="container mt-5" style="margin-top: 80px; padding-top: 50px;">
    <?php
    if (isset($_SESSION['username'])) {
        // User is logged in, display user information and additional content
  
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];

        echo "<h1>$username, Your Account</h1>";

        // User information card
        echo '<div class="card mt-6">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">User Information</h5>';
   
        echo "<p><strong>Username:</strong> $username</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo '</div>';
        echo '</div>';
        
    } else {
        // User is not logged in
        echo "<h1>Your Account</h1>";
        echo "<p>You are not logged in. Please log in to access your account.</p>";
    }
    ?>
</div>

</body>
</html>