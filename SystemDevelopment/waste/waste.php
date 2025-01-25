<?php
session_start();
include('db_connection.php');

if (isset($_POST['logout'])) {
    // Clear all session variables
    session_unset();
    session_destroy();
    
    // Redirect to the homepage after logout
    header('Location: homepage.php'); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Wisdom</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/waste.css">
   
  
</head>
<body>


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

<?php
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        ?>

<div class="container mt-5" style="margin-top: 80px; padding-top: 10px;">
    <?php
    // Display a message if the user is not logged in
    if (!isset($_SESSION['user_id'])) {
        echo '<div class="alert alert-danger" role="alert">';
        echo 'Please log in to your account to access the webpage.';
        echo '</div>';
    } else {
        // User is logged in, display the waste capture and upload sections
        echo '<h1>Capture Your Waste</h1>';

        // Camera Section
        echo '<div class="row">';
        echo '<div class="col-md-6" display: flex; justify-content: center;>';
        echo '<h2>Capture from Camera</h2>';
        echo '<video id="video" width="500" height="300" autoplay></video>';
        echo '<canvas id="canvas" style="display: none;" width="300" height="225"></canvas>';
    
        echo '<span id="reminder" style="color: red; font-style: italic; animation: flashReminder 1.5s infinite;">Please ensure a clear background when capturing the waste image.</span>';
        echo '<br>';
        echo '<br>';
        echo '<button id="captureBtn" class="modern-button">Capture</button>';
        echo '</div>';
        echo '<style>';
        echo '@keyframes flashReminder {
                0% { opacity: 1; }
                50% { opacity: 0; }
                100% { opacity: 1; }
            }';
        echo '</style>';

        // Hidden form for capturing and submitting the image
        echo '<form id="captureForm" action="result.php" method="post">';
        echo '<input type="hidden" name="file" id="capturedImageData">';
        echo '</form>';
        

        // Upload Section
        echo '<div class="col-md-6" style="margin-top: -20px; display: flex; justify-content: center;">';
        echo '<div style="margin-top:20px">';
        echo '<h2>Upload</h2>';

        echo '<form id="uploadForm" action="result.php" method="post" enctype="multipart/form-data">';
        
        echo '<label id="uploadLabel" for="file" style="width: 500px; height: 300px; display: block; border: 3px dashed #ddd; justify-content: center; align-items: center; border-radius: 8px; overflow: hidden; position: relative; cursor: pointer;">';
        echo '<img id="uploadImage" src="icons/imageUpload.png" alt="Click to Upload" style="width: 50px; height: 50px; object-fit: cover; justify-content: center; align-items: center;">';
        echo '<input type="file" name="file" id="file" accept="image/*" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" onchange="displayImage(this);" required>';
        echo '<label for="file">Click to Upload</label>';
        echo '</label>';
        echo '<br>';
        echo '<input type="submit" class="modern-button2" value="Predict" style= "display: flex; justify-content: center;">';
        echo '</form>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>

<script>
    // Function to capture image from camera
    function captureImage() {
        var video = document.getElementById('video');
        var canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');

        // Check if the browser supports getUserMedia
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                })
                .catch(function (error) {
                    console.error('Error accessing camera:', error);
                });
        }

        // Capture image from video stream
        document.getElementById('captureBtn').addEventListener('click', function () {
            context.drawImage(video, 0, 0, 300, 225);
            var imgData = canvas.toDataURL('image/jpeg');

            // Set the captured image data to the hidden input field
            document.getElementById('capturedImageData').value = imgData;

            // Submit the form
            document.getElementById('captureForm').submit();
        });
    }

    // Invoke the captureImage function when the page loads
    window.onload = function () {
        captureImage();
    };
</script>


</div>

<?php
    } else {
        // User is not logged in
        echo '<div class="container mt-5">';
        echo '<h1>Waste Prediction</h1>';
        echo '<p>You are not logged in. Please log in to access this feature.</p>';
        echo '</div>';
    }
    ?>
    



<script>
    function displayImage(input) {
    const uploadImage = document.getElementById('uploadImage');
    const uploadLabel = document.getElementById('uploadLabel');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            uploadImage.src = e.target.result; // Update the src attribute with the data URL
            uploadImage.style.width = '100%'; // Ensure the image fills the container
            uploadImage.style.height = '100%';
            uploadImage.style.objectFit = 'cover'; // Maintain aspect ratio
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>




</body>
</html>