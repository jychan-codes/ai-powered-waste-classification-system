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
    <title>Waste Wisdom</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="nearme.css">
    <link rel="stylesheet" type="text/css" href="css/nearme.css">
     <!-- Include jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  
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
    // User is logged in, display the embedded Google My Maps
    ?>
    <!-- Row of clickable icons -->
    <div class="map-icons" style="margin-top: 80px; padding-top: 5px;">
        <!-- Container for Furniture icon -->
        <div class="map-icon-container" onmouseenter="showDetails('furniture', event)" onmouseleave="hideDetails('furniture')" onclick="showMap('furniture')">
            <img src="icons/furniture.png" alt="Furniture">
            <div class="icon-text">Furniture</div>
        </div>
        <!-- Container for E-Waste icon -->
        <div class="map-icon-container" onmouseenter="showDetails('ewaste', event)" onmouseleave="hideDetails('ewaste')"onclick="showMap('ewaste')">
            <img src="icons/smartphone.png" alt="Ewaste">
            <div class="icon-text">E-Waste</div>
        </div>
        <!-- Container for Cloth icon -->
        <div class="map-icon-container" onmouseenter="showDetails('cloth', event)" onmouseleave="hideDetails('cloth')"onclick="showMap('cloth')">
            <img src="icons/cloth.png" alt="Cloth">
            <div class="icon-text">Clothes</div>
        </div>
        <!-- Container for Book icon -->
        <div class="map-icon-container" onmouseenter="showDetails('book', event)" onmouseleave="hideDetails('book')"onclick="showMap('book')">
            <img src="icons/book.png" alt="Book">
            <div class="icon-text">Book</div>
        </div>
        <!-- Container for Recycle icon -->
        <div class="map-icon-container" onmouseenter="showDetails('recycle', event)" onmouseleave="hideDetails('recycle')"onclick="showMap('recycle')">
            <img src="icons/recycle.png" alt="Recycle">
            <div class="icon-text">Recycle</div>
        </div>
        <!-- Container for Beauty icon -->
        <div class="map-icon-container" onmouseenter="showDetails('beauty', event)" onmouseleave="hideDetails('beauty')"onclick="showMap('beauty')">
            <img src="icons/skincare.png" alt="Beauty">
            <div class="icon-text">Beauty</div>
        </div>
        <!-- Container for Battery icon -->
        <div class="map-icon-container" onmouseenter="showDetails('battery', event)" onmouseleave="hideDetails('battery')"onclick="showMap('battery')">
            <img src="icons/battery.png" alt="Battery">
            <div class="icon-text">Battery</div>
        </div>
    </div>

<div id="map-container">
    <iframe
        id="map"
        frameborder="0"
        width="100%"
        height="570"
        style="min-height: 490px;"
        allowfullscreen
        src="https://www.google.com/maps/d/u/0/embed?mid=1VrVAtZXijXUcMM2bKF3MJkotp9tN-GA&ehbc=2E312F&noprof=1"
    ></iframe>
</div>

<?php
} else {
    // User is not logged in
    echo '<div class="container mt-5" style="margin-top: 80px; padding-top: 50px;">';
    echo '<h1>Recycling Center Map</h1>';
    echo '<p>You are not logged in. Please log in to access the map.</p>';
    echo '</div>';
}
?>

<script>
    // Function to show the corresponding map based on the clicked icon
    function showMap(mapType) {
        // Update the iframe source based on the mapType
        var iframeSrc = '';

        switch (mapType) {
            case 'furniture':
                iframeSrc = 'https://www.google.com/maps/d/u/0/embed?mid=1_fwIY-yFo63F9MEelf3U5fDLBX2VxKI&ehbc=2E312F&noprof=1';
                break;
            case 'ewaste':
                iframeSrc = 'https://www.google.com/maps/d/u/0/embed?mid=1Kt20cFEZtXwO07l7YwQf_HCVuSPykUo&ehbc=2E312F&noprof=1';
                break;
            case 'cloth':
                iframeSrc = 'https://www.google.com/maps/d/u/0/embed?mid=1DiBayitCMCQZrtGmTiw55xkpULYnQVk&ehbc=2E312F&noprof=1';
                break;
            case 'book':
                iframeSrc = 'https://www.google.com/maps/d/u/0/embed?mid=1ziDzIOsO_CR9ohmkUaVWzpRAz59JPOs&ehbc=2E312F&noprof=1';
                break;
            case 'recycle':
                iframeSrc = 'https://www.google.com/maps/d/u/0/embed?mid=1VrVAtZXijXUcMM2bKF3MJkotp9tN-GA&ehbc=2E312F&noprof=1';
                break;
            case 'beauty':
                iframeSrc = 'https://www.google.com/maps/d/u/0/embed?mid=1KpFGvY2XYkWDyQm_3cp8KMLX0ElOl7k&ehbc=2E312F&noprof=1';
                break;
            case 'battery':
                iframeSrc = 'https://www.google.com/maps/d/u/0/embed?mid=1hl9Nj88APRi3TwOs3il_SnKQ6BrEpCg&ehbc=2E312F&noprof=1';
                break;
            default:
                break;
        }
        // Set the iframe source and display the map container
        $('#map').attr('src', iframeSrc);
    }
</script>

<script>
function showDetails(type, event) {
    // Create and position the pop-out box
    var detailsBox = document.createElement('div');
    detailsBox.className = 'pop-out-box';

    // Define the details based on the type
    var details = '';
    switch (type) {
        case 'furniture':
            details = ['Sofa', 'Table', 'Chair', 'Desk', 'Cabinet'];
            break;
        case 'ewaste':
            details = ['Laptop', 'Smartphone', 'Tablet', 'Desktop Computer', 'Television', 'Refrigerator', 'Washing Machine'];
            break;
        case 'cloth':
            details = ['T-shirt', 'Jeans', 'Dress', 'Pants', 'Shorts'];
            break;
        case 'book':
            details = ['Novel', 'Textbook', 'Magazine', 'Cookbook'];
            break;
        case 'recycle':
            details = ['Plastic Bottles', 'Glass Jars', 'Aluminum Cans', 'Paper', 'Cardboard'];
            break;
        case 'beauty':
            details = ['Skincare Container', 'Serums Glass Jar', 'Sunscreen Tubes', 'Handcream Tube', 'Plastic Pump Bottles'];
            break;
        case 'battery':
            details = ['Alkaline Batteries (AA, AAA)', 'Lead-Acid Batteries (Used for car)', 'Rechargeable Batteries (Used for laptop/phone)', 'Button Cell Batteries (Used for watches/calculators)', 'Zinc-Carbon Batteries (Used for clocks/radios)'];
            break;
        default:
            details = ['No details found'];
    }

    // Create a list element
    var list = document.createElement('ul');
    // Iterate through details and create list items
    details.forEach(function(detail) {
        var listItem = document.createElement('li');
        listItem.textContent = detail;
        list.appendChild(listItem);
    });

    // Append the list to the pop-out box
    detailsBox.appendChild(list);

    // Position the pop-out box
    detailsBox.style.top = (event.clientY + 10) + 'px'; // Position box below the pointer
    detailsBox.style.left = (event.clientX + 10) + 'px'; // Position box to the right of the pointer

    // Add the pop-out box to the document body
    document.body.appendChild(detailsBox);

    // Store the created pop-out box reference
    event.target.dataset.detailsBox = detailsBox;
}

function hideDetails(type) {
    // Remove the pop-out box from the document body
    var detailsBox = document.querySelector('.pop-out-box');
    if (detailsBox) {
        detailsBox.parentNode.removeChild(detailsBox);
    }
}
</script>



</body>
</html>