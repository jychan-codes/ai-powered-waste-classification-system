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
    <link rel="stylesheet" type="text/css" href="css/result.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

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



<!-- Container for Content -->
<div class="container" style="margin-top: 20px; padding: 60px; max-width: 1200px;max-height: 650px;margin-bottom: 10px; padding-top: 50px; margin-top: 80px; ">
<?php



// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or take appropriate action
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "wasteV5");
    $target_url = 'http://127.0.0.1:5000/predict';

    // Check if file is uploaded or image is captured
    $image_data = '';
    $file_name_with_full_path = '';

    if (isset($_FILES['file']['tmp_name'])) {
        // File upload
        $file_name_with_full_path = $_FILES['file']['tmp_name'];
        $image_data = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($file_name_with_full_path));
    } elseif (isset($_POST['file'])) {
        // Captured image from camera
        $image_data = $_POST['file'];

        // Decode the captured image data
        $decoded_image_data = base64_decode(explode(',', $image_data)[1]);

        // Save the image data to a file
        $image_path = 'images/' . uniqid() . '.jpg';
        file_put_contents($image_path, $decoded_image_data);

        $file_name_with_full_path = $image_path;
    }


    // Initialize cURL session
    $ch = curl_init($target_url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => new CURLFile($file_name_with_full_path)]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and get the response
    $response = curl_exec($ch);

    // Close cURL session
    curl_close($ch);

    // Decode the JSON response
    $result = json_decode($response, true);


    if (isset($_SESSION['user_id'])) {
        $stmt = $conn->prepare("INSERT INTO waste_classification (user_id, image_path, main_category) VALUES (?, ?, ?)");

        // Assuming 'prediction' contains the category
        $category = $result['prediction'] ?? 'N/A';

        // Bind parameters
        $stmt->bind_param("sss", $_SESSION['user_id'], $file_name_with_full_path, $category);

        // Execute the statement
        $stmt->execute();

        // Close the statement
        $stmt->close();
    }

    // Close the database connection
    $conn->close();
}
?>

 <!-- Content Wrapper -->
 <div style="display: flex; flex-wrap: wrap;">

<!-- Uploaded/Captured Image -->
<div style="flex: 1; margin-right: 20px;">
    <h3>Uploaded/Captured Image:</h3>
    <div style="position: relative; display: inline-block; border-radius: 50%; overflow: hidden;">
        <img src="<?php echo $image_data; ?>" width="300" height="300" style="border-radius: 50%;" />
    </div>

 <!-- Waste Classification Result -->
 <h3>Waste Classification Result:</h3>
    <p><strong>Prediction:</strong> <?php echo ($result['prediction'] ?? 'N/A'); ?></p>

    <?php
    $type_of_waste = 'N/A';
        if(isset($result['prediction'])) {
            $recyclable_waste = array('cardboard', 'glass', 'paper', 'plastic', 'metal');
            $e_waste = array('battery', 'charger', 'mouse', 'laptop', 'smartphone');
            $organic_waste = array('organic');
        if(in_array($result['prediction'], $recyclable_waste)) {
            $type_of_waste = 'Recyclable Waste';
        } if(in_array($result['prediction'], $organic_waste)) {
            $type_of_waste = 'Organic Waste'; 
        } elseif(in_array($result['prediction'], $e_waste)) {
            $type_of_waste = 'E-Waste';
        }
    }
    ?>

    <p><strong>Type of Waste:</strong> <?php echo $type_of_waste; ?></p>
    <p><strong>Confidence Score:</strong> <?php echo ($result['confidence'] ?? 'N/A'); ?></p>
    
    <br>
    <button id="toggleGuidelines" class="modern-button" style="margin-top:-10px;">Show Disposal Tips</button>
    </div>

 <!-- Vertical line between classification result and guidelines -->
 <div id="verticalLine" style="height: 500px; border-left: 1px solid black; margin: 0 20px; display: none;"></div>


<!-- Container for Guidelines -->
<div id="guidelinesContainer" style="display: none; text-align: center; max-height: 500px; max-width: 450px; margin-top: 20px; padding: 50px; background-color: white; border-radius: 8px;">
    <div id="guidelinesContent"></div>
</div>

<!-- Add this script block after your existing JavaScript code -->
<script>
    // Sample waste disposal guidelines
    const wasteGuidelines = {
        battery: "Dispose of batteries at designated battery recycling points.",
        cardboard: "Recycle cardboard in your local recycling program.",
        charger: "Dispose of chargers at electronic waste recycling centers.",
        glass: "Recycle glass bottles and containers in your area.",
        laptop: "Donate or recycle old laptops responsibly.",
        metal: "Recycle metal items at your local recycling facility.",
        mouse: "Recycle electronic accessories like mice through e-waste programs.",
        organic: "Compost organic waste for a sustainable disposal method.",
        paper: "Recycle paper products in your local recycling program.",
        plastic: "Recycle plastic containers and bags at your local recycling center.",
        smartphone: "Recycle old smartphones through electronic waste programs.",
        trash: "Dispose of general trash in designated bins.",
    };

    // Function to create icons for waste categories
    function createIconsElements(result) {
        // Implementation to create icons for each waste category
        
    }

    // Function to update guidelines text based on waste category
    function updateGuidelinesText(result) {
        const guidelinesContainer = document.getElementById("guidelinesContent");
        const category = result.prediction || 'N/A';
        const guidelinesText = wasteGuidelines[category] || 'No guidelines available for this category.';

        // Clear existing content before adding new content
        guidelinesContainer.innerHTML = '';

        //guidelinesContainer.innerHTML = <p>${guidelinesText}</p>;
        if (['paper', 'glass', 'metal', 'plastic', 'trash'].includes(category)) {
            // Display recycling bin icons with circles
            const recyclingBinColors = {
                paper: 'blue',
                glass: 'brown',
                metal: 'orange',
                plastic: 'orange',
                trash: 'black', 
            };

            // Function to draw the animated circle
        function drawAnimatedCircle(container, radius) {
        const circle = document.createElement('div');
        circle.style.position = 'absolute';
        circle.style.top = '30%';
        circle.style.left = '50%';
        circle.style.transform = 'translate(-50%, -50%)';
        circle.style.border = '4px solid red';
        circle.style.borderRadius = '50%';
        circle.style.width = `${radius}px`;
        circle.style.height = `${radius}px`;

        // Append the circle to the container
        container.appendChild(circle);

        let startTime;

        // Animation function
        function animate(time) {
            if (!startTime) startTime = time;
            const progress = time - startTime;
            if (progress < 1000) {
                // Gradually increase the circle size over 1 second
                const newRadius = (progress / 1000) * radius;
                circle.style.width = `${newRadius}px`;
                circle.style.height = `${newRadius}px`;
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    }

            guidelinesContainer.innerHTML += `
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div style="background-color: #f0f0f0; padding: 10px; text-align: center; margin-bottom: 10px;">
                <span><strong>WHAT TO DO</strong></span>
            </div>
            <div style="display: flex; align-items: center; justify-content: center;">
                ${['paper', 'glass', 'metal', 'plastic', 'trash'].map(bin => `
                    <div style="position: relative; display: flex; flex-direction: column; align-items: center; margin-right: 10px;">
                        <i class="fas fa-recycle" style="font-size: 30px; color: ${recyclingBinColors[bin]};"></i>
                        <div style="margin-top: 5px; color: ${recyclingBinColors[bin]};">${getBinText(bin)}</div>
                    ${bin === category ? `<div id="animatedCircleContainer" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>` : ''}
            </div>
            `).join('')}
            </div>
            <div style="margin-top: 10px;">
                <span>Throw it to the <strong>respective bin</strong></span>
                <br>
                <span>Make sure it is <strong>CLEAN</strong></span>
            </div>
            </div>`;

            function getBinText(bin) {
                const binTexts = {
                    paper: 'PaperBin',
                    glass: 'GlassBin',
                    metal: 'MetalBin',
                    plastic: 'PlasticBin',
                    trash: 'TrashBin',
                };
            return binTexts[bin] || 'Unknown Bin';
        }

    // After updating the guidelinesContainer.innerHTML, initiate the animation
    const animatedCircleContainer = document.getElementById('animatedCircleContainer');
        if (animatedCircleContainer) {
        drawAnimatedCircle(animatedCircleContainer, 60); // Adjust the initial radius as needed
    }

        } else if (['battery', 'cardboard', 'charger', 'laptop', 'mouse', 'organic', 'smartphone'].includes(category)) {
            // Display disposal method for other categories
            const disposalMethods = {
                battery: "<strong>DO NOT</strong> throw to rubbish bin",
                cardboard: "Reuse - Keep it for DIY-purposes / Donate",
                charger: "<strong>DO NOT</strong> throw to rubbish bin",
                laptop: "<strong>DO NOT</strong> throw to rubbish bin <br> <strong>DELETE</strong> personal data <br> <strong>RESET</strong> device",
                mouse: "<strong>DO NOT</strong> throw to rubbish bin",
                organic: "<p><strong><a href='https://www.youtube.com/watch?v=zy70DAaeFBI' target='_blank'>Compost</a></strong> organic waste and use it as fertilizer. Start with purchasing a <strong><a href='https://shopee.com.my/Home-Food-Waste-Composting-Bokashi-Terra-Bin-i.65628278.1276821012' target='_blank'>compost bin</a></strong>!</p>",
                smartphone: "<strong>DO NOT</strong> throw to rubbish bin <br> <strong>DELETE</strong> personal data <br> <strong>RESET</strong> device",
            };

            guidelinesContainer.innerHTML += `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <div style="background-color: #f0f0f0; padding: 10px; text-align: center;  margin-bottom: 10px;">
                        <span><strong>WHAT TO DO</strong></span>
                    </div>
                    <div>
                        <p>${disposalMethods[category]}</p>
                    </div>
                </div>`;
        } 
        else {
            //Handle the case if the category is not recognized
            guidelinesContainer.innerHTML += `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <div style="background-color: #f0f0f0; padding: 10px; text-align: center; margin-top: 20px;">
                        <span><strong>WHAT TO DO</strong></span>
                    </div>
                    <div>
                        <p>No guidelines available for this category.</p>
                    </div>
                </div>`;
        }

        
    }

    // Function to toggle visibility of guidelines container
    function toggleGuidelines() {
        const guidelinesContainer = document.getElementById("guidelinesContainer");
        const verticalLine = document.getElementById("verticalLine");
        guidelinesContainer.style.display = (guidelinesContainer.style.display === "none") ? "block" : "none";
    
       // Toggle the display property of the vertical line based on guidelines container visibility
    verticalLine.style.display = (guidelinesContainer.style.display === "none") ? "none" : "block";
    }

    // Initial setup
    const result = <?php echo json_encode($result); ?>;
    createIconsElements(result);

    // Add event listener to toggle visibility and update guidelines
    document.getElementById("toggleGuidelines").addEventListener("click", function () {
        toggleGuidelines();
      
        updateGuidelinesText(result);
    });

   
    
   


    
    // Print PHP variables into JavaScript
    const category = <?php echo json_encode($category); ?>;
    
    const alternativeLink = <?php
        switch ($category) {
            case 'paper':
            case 'plastic':
            case 'metal':
            case 'glass':
                echo '"<span class=\"send-to-text\">Send to</span><a href=\"https://www.google.com/maps/d/u/0/embed?mid=1VrVAtZXijXUcMM2bKF3MJkotp9tN-GA&ehbc=2E312F&noprof=1\" target=\"_blank\" class=\"recycling-center-link\">Recycling Center</a>"';
                break;

            case 'cardboard':
                echo '"<span class=\"send-to-text\">Send to</span><a href=\"https://www.google.com/maps/d/u/0/embed?mid=1VrVAtZXijXUcMM2bKF3MJkotp9tN-GA&ehbc=2E312F&noprof=1\" target=\"_blank\" class=\"recycling-center-link\">Recycling Center</a>"';
                break;

            case 'laptop':
            case 'smartphone':
            case 'charger':
            case 'mouse':
                echo '"<span>Donate if is still in good condition</span><br><span class=\"send-to-text\">Send to</span><a href=\"https://www.google.com/maps/d/u/0/embed?mid=1Kt20cFEZtXwO07l7YwQf_HCVuSPykUo&ehbc=2E312F&noprof=1\" target=\"_blank\" class=\"recycling-center-link\">E-Waste Collection Center</a>"';
                break;

            case 'battery': 
                echo '"<span class=\"send-to-text\">Send to</span><a href=\"  https://www.google.com/maps/d/u/0/embed?mid=1hl9Nj88APRi3TwOs3il_SnKQ6BrEpCg&ehbc=2E312F&noprof=1\" target=\"_blank\" class=\"recycling-center-link\">Battery Recycling Center</a>"';
            
                break;
            
            case 'organic': 
                echo '"<span class=\"send-to-text\">Support</span><a href=\"https://www.youtube.com/watch?v=q5MqEfF6NCs\" target=\"_blank\" class=\"recycling-center-link\">FOLO Farm.</a> Their vegetables are powered by food waste compost!"';
              
                break;

            case 'trash':
                echo '"<span>-</span>"';
                break;
            default:
                echo '"<span>Not Available</span>"';
        }
    ?>;
    guidelinesContainer.innerHTML += `
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 20px">
        <div style="background-color: #f0f0f0; padding: 10px; text-align: center; margin-bottom: 10px;">
            <span><strong>ALTERNATIVE WAY</strong></span>
        </div>
        <div>
            ${alternativeLink}
        </div>
    </div>`;



    const doItBetter = <?php
        switch ($category) {
            case 'paper':
                echo '"<p>Paperless - Embrace digital platform</p>"';
                break;
            case 'plastic':
                echo '"<p><strong>USE-YOUR-OWN</strong> reusable container</p>"';
                break;
            case 'metal':
                echo '"<p><strong>REDUCE</strong> metal waste by <strong>REUSE</strong> (mending, crafts, decorating, and gardening) or <STRONG>DONATE</STRONG> to charities, schools, community centres, and other organisations</p>"';
                break;
            case 'glass':
                echo '"<span class=\"send-to-text\">Keep it for DIY purpose</span><a href=\"  https://www.youtube.com/watch?v=xptLVkBKmHg\" target=\"_blank\" class=\"recycling-center-link\">No idea? Click Me</a>"';
                break;
            case 'cardboard':
                echo '"<p>Avoid Contamination - Remove Tape and Labels</p>"';
                break;
            case 'laptop':
                echo '"<p>Consider upgrading components instead of replacing the entire laptop</p>"';
                break;
                case 'smartphone':
                echo '"<p>Consider repairing or upgrading components instead of replacing the entire device</p>"';
                break;
            case 'battery':
                echo '"<p>Use rechargeable batteries</p>"';
                
                break;
            case 'charger':
                echo '"<p>Use cable protectors</p>"';
                break;
            case 'mouse':
                echo '"<p>-</p>"';
                break;
            case 'organic':
                echo '"<span>Minimize food waste by planning meals and storing food properly</span>"';
                break;
            case 'trash':
                echo '"<span class=\"send-to-text\">Explore TedTalk to get inspiration on</span><a href=\"  https://www.youtube.com/watch?v=1TDC-Zud_uM\" target=\"_blank\" class=\"recycling-center-link\">Reduce Waste</a>"';
                break;
            default:
                echo '"<p>-</p>"';
        }
    ?>;
    guidelinesContainer.innerHTML += `
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 20px">
        <div style="background-color: #f0f0f0; padding: 10px; text-align: center; margin-bottom: 10px;">
            <span><strong>DO IT BETTER</strong></span>
        </div>
        <div>
            ${doItBetter}
        </div>
    </div>`;
</script>


<script>

    // Function to toggle visibility of guidelines container
    function toggleGuidelines() {
        const guidelinesContainer = document.getElementById("guidelinesContainer");
        guidelinesContainer.style.display = (guidelinesContainer.style.display === "none") ? "block" : "none";
        //const verticalLine = document.getElementById("verticalLine");
    
    // Hide the "Show Guidelines" element after clicking
    toggleGuidelinesElement.style.display = "none";

     // Toggle the display property of the guidelines container
     //guidelinesContainer.style.display = (guidelinesContainer.style.display === "none") ? "block" : "none";

    // Toggle the display property of the vertical line based on guidelines container visibility
    //verticalLine.style.display = (guidelinesContainer.style.display === "none") ? "none" : "block";
    }

    // Initial setup
    const result = <?php echo json_encode($result); ?>;
    createIconsElements(result);

// Add event listener to toggle visibility and update guidelines
    document.getElementById("toggleGuidelines").addEventListener("click", function () {
        //toggleGuidelines();
        //updateGuidelinesText(result);
    });
</script>




<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>