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
    <link rel="stylesheet" type="text/css" href="css/homepage.css">
    <script src="js/homepage.js"></script>
    <!-- Load Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Load Chart.js datalabels plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

  
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light fixed-top" style="background-color:  #203E32">
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
                    echo '<li class="nav-item"><form method="post"><button type="submit" name="logout" class="btn btn-link" style="color: #FEF9E0;"><img src="icons/logout.png" alt="Logout" style="width: 20px; height: 20px; margin-right: 5px;">Logout</button></form></li>';
                } else {
                    // User is not logged in, display "Login"
                    echo '<li class="nav-item"><a class="nav-link" href="login.php" style="color: #F2F3EE;">Login</a></li>';
                }
            ?>
        </ul>
    </div>
</nav>



<!-- Homepage Content -->
<div class="container mt-5" style="background: url('icons/background3.png') center center fixed; background-size: cover; color: #fff; height: 400px; width: 1000; margin-top: 80px; padding-top: 50px; " >

<?php
    if (isset($_SESSION['username'])) {
        // User is logged in, display user information and order history
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        
        echo "<h1>Welcome $username!</h1>";
    }
    else{
        echo "<h1>Welcome!</h1>";
    }
        ?>


<p>Empowering Malaysians to Segregate Waste for a Sustainable Future</p>
    <!-- <p>Discover a world of zero waste!</p> -->
</div>

<!-- Waste Hierarchy -->
<div class="container mt-5 text-center container-section">
    <h2 class="section-title">Waste Hierarchy</h2>
    <p class="section-description" style="margin-bottom: 10px;">Reduce, Reuse, Recycle, Recover, Dispose - Your Pathway to Zero Waste!</p>

    <div class="waste-hierarchy">
        <div class="layer" onclick="showDetails('prevention')">Prevention</div>
        <div class="connector">></div>
        <div class="layer" onclick="showDetails('reuse')">Reuse</div>
        <div class="connector">></div>
        <div class="layer" onclick="showDetails('recycling')">Recycling</div>
        <div class="connector">></div>
        <div class="layer" onclick="showDetails('recovery')">Recovery</div>
        <div class="connector">></div>
        <div class="layer" onclick="showDetails('disposal')">Disposal</div>
    </div>
    <br>
    <div id="layer-details"></div>
</div>

<!-- Short line separator -->
<div class="separator"></div>

<!-- Plastic Types Section -->
<div class="container mt-5 text-center  container-section">
    <h2 class="section-title">Plastic Types 1-7</h2>
    <p class="section-description">Understanding the different types of plastics (1-7) is crucial for proper recycling. Here's a quick guide:</p>
    <div class="plastic-types-row">
        <div class="plastic-type" onmouseover="hoverEffect(this, 'icons/type1.jpg', 'Yes', 'Mineral Bottles')" onmouseout="resetImage(this, 'icons/1.jpg')">
            <img src="icons/1.jpg" alt="Plastic Type 1">
            <img src="icons/type1.jpg" alt="Plastic Type 1 Hover" class="hover-image" style="display: none;">
        </div>
        
        <div class="plastic-type" onmouseover="hoverEffect(this, 'icons/type2.jpg', 'Yes', 'Shampoo Containers, Dish Wash Bottles')" onmouseout="resetImage(this, 'icons/2.jpg')">
            <img src="icons/2.jpg" alt="Plastic Type 2">
            <img src="icons/type2.jpg" alt="Plastic Type 2 Hover" class="hover-image" style="display: none;">
        </div>
        
        <div class="plastic-type" onmouseover="hoverEffect(this, 'icons/type3.jpg', 'No', 'Pipes, Credit Cards')" onmouseout="resetImage(this, 'icons/3.jpg')">
            <img src="icons/3.jpg" alt="Plastic Type 3">
            <img src="icons/type3.jpg" alt="Plastic Type 3 Hover" class="hover-image" style="display: none;">
        </div>
        
        <div class="plastic-type" onmouseover="hoverEffect(this, 'icons/type4.jpeg', 'No', 'Bubble Wrap, Plastic Bags, Garbage Bag' )" onmouseout="resetImage(this, 'icons/4.jpg')">
            <img src="icons/4.jpg" alt="Plastic Type 4">
            <img src="icons/type4.jpeg" alt="Plastic Type 4 Hover" class="hover-image" style="display: none;">
        </div>
        
        <div class="plastic-type" onmouseover="hoverEffect(this, 'icons/type5.jpg', 'Yes', 'Tupperware, Medicine Bottles, Yogurt Containers')" onmouseout="resetImage(this, 'icons/5.jpg')">
            <img src="icons/5.jpg" alt="Plastic Type 5">
            <img src="icons/type5.jpg" alt="Plastic Type 5 Hover" class="hover-image" style="display: none;">
        </div>
        
        <div class="plastic-type" onmouseover="hoverEffect(this, 'icons/type6.jpg', 'No', 'Plastic Cutlery, Take-Away Packaging')" onmouseout="resetImage(this, 'icons/6.jpg')">
            <img src="icons/6.jpg" alt="Plastic Type 6">
            <img src="icons/type6.jpg" alt="Plastic Type 6 Hover" class="hover-image" style="display: none;">
        </div>
        
        <div class="plastic-type" onmouseover="hoverEffect(this, 'icons/others.jpg', 'No', 'Baby Bottles, Phone Covers, CDs, Toys')" onmouseout="resetImage(this, 'icons/7.jpg')">
            <img src="icons/7.jpg" alt="Plastic Type 7">
            <img src="icons/others.jpg" alt="Plastic Type 7 Hover" class="hover-image" style="display: none;">
        </div>
    </div>
    <br>
    <br>
    <p style="color:#636363;">Only plastic <span class="large"> Type 1, 2 and 5</span> are <strong>recyclable</strong> in Malaysia, <strong>others</strong> are <span class="large"> Trash</span></p>
    <p style="color:#636363; ">Collect as many as you can and send them <a href="https://www.google.com/maps/d/u/0/embed?mid=1VrVAtZXijXUcMM2bKF3MJkotp9tN-GA&ehbc=2E312F&noprof=1" target="_blank" style="color: #203E32; font-weight: bold;">HERE</a>!</p>
</div>

<br>
<!-- Short line separator -->
<div class="separator"></div>

<!-- E-Waste -->
<div class="container mt-5 text-center  container-section">
    <h2 class="section-title">How Much You Know about E-Waste?</h2>
    <p class="section-description" style="margin-bottom: 10px;">Imagine “loads and loads” and you’ll be halfway to the amount of e-waste being produced</p>
    <p class="section-description"><a href="https://weee-forum.org/iewd-about/" target="_blank" style="color: #203E32; font-weight: bold;">International E-Waste Day</a> is held on <strong>14 October</strong> every year</p>
    
    <div class="plastic-types-row">
    <canvas id="ewasteChart"></canvas>
        <div class="info">
            <h5>Common E-Waste Items Generated</h5>
            
            <p><span class="large">32%</span> - Small Equipment</p>
            <p><span class="large">24%</span> - Large Equipment</p>

            <br>
            <p>Around <span class="large">364 </span> Million Kilogrammes of e-waste are produced annually in Malaysia, equivalent to
            <span class="large">805 KLCC Tower</span></p>
            <p>E-Waste recycling rates are still only around <span class="large">17%</span></p>
            
        </div>
        </div>
        <div>
        <h5 id="ewaste-toggle" style="cursor: pointer;">Click to know E-Waste in Malaysia</h5>
        </div>
        <br>
        <!-- Table for E-Waste in Malaysia -->
        <div id="ewaste-table" style="display: none;">
        <?php
        // Connect to database
        $conn = mysqli_connect("localhost", "root", "", "wastev5");

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieve data from the table
        $sql = "SELECT no, description FROM ewaste_data";
        $result = mysqli_query($conn, $sql);

        // Display data in a table
        if (mysqli_num_rows($result) > 0) {
            
            echo "<table>";
            echo "<tr><th>List of E-Waste</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo " </td><td>" . $row["description"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        // Close the connection
        mysqli_close($conn);
        ?>
</div>
<br>
<p style="color:#636363; ">You may recycle anything with a plug, battery or cable <a href="https://www.google.com/maps/d/u/0/embed?mid=1Kt20cFEZtXwO07l7YwQf_HCVuSPykUo&ehbc=2E312F&noprof=1" target="_blank" style="color: #203E32; font-weight: bold;">HERE</a>!</p>
</div>
        
<br>
<!-- Short line separator -->
<div class="separator"></div>
        
<!-- Organic -->
<div class="container mt-5 text-center container-section">
    <h2 class="section-title">Organic Waste</h2>
    <p class="section-description" style="margin-bottom: 10px;">Think Twice Before Wasting Food</p>
    <br>
    <!-- Types of Organic Waste -->
    <div class="organic-waste-types">
        <h5>Types of Organic Waste</h5>
        <div class="map-icons" style="padding-top: 5px; display: flex;">
            <!-- Food Waste -->
            <div class="map-icon-container" onclick="showWasteExamples('food')">
                <img src="icons/foodwaste.png" alt="Food Waste">
                <div class="icon-text">Food Waste</div>
                <!-- Container for displaying examples -->
                <div class="examples-container" id="food-examples">
                    <h3>Food Waste Examples</h3>
                    <p>Plate scrapings and uneaten leftovers </p>
                    <p>Spoiled or expired food (expired dairy products) </p>
                    <p>Food packaging (if made from organic materials and not recyclable) </p>
                    <p>Leftover food scraps
                    (e.g., fruit and vegetable peels, eggshells, coffee grounds)
                    </p>
                </div>
            </div>
            <!-- Yard Waste -->
            <div class="map-icon-container" onclick="showWasteExamples('yard')">
                <img src="icons/yardwaste.png" alt="Yard Waste">
                <div class="icon-text">Yard Waste</div>
                <!-- Container for displaying examples -->
                <div class="examples-container" id="yard-examples">
                    <h3>Yard Waste Examples</h3>
                    <p>Grass clippings</p>
                    <p>Leaves and twigs</p>
                    <p>Pruned branches and shrubs</p>
                    <p>Weeds and garden trimmings</p>
                    <p>Plant-based mulch</p>
                </div>
            </div>
            <!-- Paper Products -->
            <div class="map-icon-container" onclick="showWasteExamples('paper')">
                <img src="icons/paper.png" alt="Paper Products">
                <div class="icon-text">Paper Products</div>
                <!-- Container for displaying examples -->
                <div class="examples-container" id="paper-examples">
                    <h3>Paper Products Examples</h3>
                    <p>Paper bags </p>
                    <p>Newspaper and magazines </p>
                    <p>Cardboard (e.g., cardboard boxes, cereal boxes)</p>
                    <p>Paper-based packaging (if not coated with non-organic materials)</p>
                    <p>Paper towels and napkins (if not contaminated with non-organic materials)</p>
                </div>
            </div>
            <!-- Agricultural Residues -->
            <div class="map-icon-container" onclick="showWasteExamples('agriculture')">
                <img src="icons/agriculture.png" alt="Agricultural Residues">
                <div class="icon-text">Agricultural Residues</div>
                <!-- Container for displaying examples -->
                <div class="examples-container" id="agriculture-examples">
                    <h3>Agricultural Residues Examples</h3>
                    <p>Crop residues (e.g., stalks, husks, stems)</p>
                    <p>Straw and hay leftover from farming operations</p>
                    <p>Organic materials used for animal bedding (e.g., sawdust, wood shavings)</p>
                    <p>Processing waste from agricultural products (e.g., fruit and vegetable processing waste)</p> 
                </div>
            </div>
        </div>
    </div>

    <br>
    <h5>Environmental Impact of Organic Waste</h5>
    <br>
    <div class="impact-container">
        <div class="impact-card">
            <img src="icons/climateChange.jpg" alt="Methane Emissions">
            <h3>Methane Emissions</h3>
            <p>Organic waste decomposes anaerobically in landfills, 
                producing greenhouse gas that contributes to climate change.</p>
        </div>
        <div class="impact-card">
            <img src="icons/landfillPollution.jpg" alt="Landfill Pollution">
            <h3>Landfill Pollution</h3>
            <p>Large amounts of organic waste in landfills generate leachate, a toxic liquid that can contaminate soil and water sources.</p>
        </div>
        <div class="impact-card">
            <img src="icons/ecosystemDamage.png" alt="Ecosystem Damage">
            <h3>Ecosystem Damage</h3>
            <p>Improper disposal of organic waste can harm natural ecosystems, disrupting biodiversity and affecting wildlife habitats.</p>
        </div>

    </div>

    <br>
    <h5>Tips for Reducing Food Waste</h5>
    <div class="tips-table">
    <table>
        <tr>
            <td onclick="showTipDetails('tip1')">Plan Meals Wisely</td>
            <td onclick="showTipDetails('tip2')">Store Food Properly</td>
        </tr>
        <tr>
            <td onclick="showTipDetails('tip3')">Use Leftovers Creatively</td>
            <td onclick="showTipDetails('tip4')">Compost Food Scraps</td>
        </tr>
    </table>
    <div class="tip-details">
    <div id="tip1">
        <ul>
            <li>Cook larger batches and freeze leftovers for future meals.</li>  
            <li>Choose recipes that use similar ingredients to minimize waste.</li>  
            <li>Make a weekly meal plan before going grocery shopping to avoid buying unnecessary items.</li>
            <li>Check your pantry and fridge before making a grocery list to avoid buying duplicate items.</li>
        </ul>
    </div>
    <div id="tip2">
        <ul>
            <li>Use airtight containers or wraps to keep leftovers fresh for longer.</li>
            <li>Freeze items like bread, meat, and fruits if you won't use them before they expire.</li>
            <li>Keep fruits and vegetables in the refrigerator crisper drawer to maintain freshness.</li>
            <li>Store perishable items like meat and dairy products at the back of the fridge where it's coldest.</li>
            
        </ul>
    </div>
    <div id="tip3">
        <ul>
            <li>Transform leftover vegetables into stir-fries, soups, or salads.</li>
            <li>Turn stale bread into breadcrumbs, croutons, or bread pudding.</li>
            <li>Use leftover meat or fish to make sandwiches, wraps, or casseroles.</li>
            <li>Incorporate leftover rice or pasta into frittatas, fried rice, or pasta salads.</li>
        </ul>
    </div>
    <div id="tip4">
        <ul>
            <li>Avoid composting meat, dairy, and oily foods to prevent attracting pests.</li>
            <li>Use compost as nutrient-rich fertilizer for your garden or potted plants.</li>
            <li>Consider vermiculture (worm composting) for indoor composting in small spaces.</li>
            <li>Set up a compost bin or pile in your backyard to compost fruit and vegetable scraps.</li>
        </ul>
    </div>
</div>
<br>
<p style="color:#636363; ">Lack knowledge about organic compost? Join <a href="https://www.sunway.city/kualalumpur/sustainable-living-made-easy-with-sunway-xfarms/" target="_blank" style="color: #203E32; font-weight: bold;">Sunway XFarm</a>!</p>
<p style="color:#636363; ">No idea what is OK or not OK for compost? Click <a href="https://www.thespruce.com/what-to-compost-1709069" target="_blank" style="color: #203E32; font-weight: bold;">HERE</a>!</p>
</div>


</div>

<!-- Short line separator -->
<div class="separator"></div>


<!-- Separation at Source Section -->
<div class="container-fluid mt-5">
    <div class="row">
        
        <!-- First Box -->
        <div class="col-lg-4 mb-4">
            <div class="container-section oval-container">
                <h2 class="section-title" style="margin-top: 20px;">Did You Know?</h2>
                <p class="section-description">"<strong>Separation At Source (SSI)</strong> is a sustainable waste management practice implemented by the Malaysian Government"</p>
            </div>
        </div>

        <!-- Second Box -->
        <div class="col-lg-4 mb-4">
            <div class="container-section oval-container" style="background-color:#F2F3EE">
            <h2 class="section-title" style="margin-top: 20px; color: #636363">Waste Impact</h2>
            <p class="section-description" style="color:#636363">
            "Embark on a waste-wise journey! Malaysia generates a staggering
            <strong>40,000 tonnes</strong> of waste every day – that's a colossal 1.2kg per person"
            </p>
        </div>
        </div>

        <!-- Third Box -->
        <div class="col-lg-4 mb-4">
        <div class="container-section oval-container2">
            <h2 class="section-title" style="margin-top: 20px;">Our Goal by 2025</h2>
            <p class="section-description"">
                "Reduce waste sent to landfills and 
                increase the national recycling rate to <strong>40%</strong>.
                Let's work together towards a greener future"
            </p>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add event listener to toggle the table visibility
        document.getElementById("ewaste-toggle").addEventListener("click", function() {
            var table = document.getElementById("ewaste-table");
            // Toggle the display style of the table
            table.style.display = table.style.display === "none" ? "block" : "none";
        });
    });
</script>



<!-- JavaScript Functions -->
<script>
    function showWasteExamples(type) {
    // Get the examples container for the selected type
    var examplesContainer = document.getElementById(type + '-examples');
    
    // Toggle the display of the examples container
    if (examplesContainer) {
        if (examplesContainer.style.display === 'block') {
            examplesContainer.style.display = 'none'; // If currently visible, hide it
        } else {
            examplesContainer.style.display = 'block'; // If currently hidden, show it
        }
    }
}
</script>

<script>
/* JavaScript for Tips for Reducing Food Waste section */
function showTipDetails(tipId) {
    var selectedTip = document.getElementById(tipId);
    
    // Toggle the display of tip details
    if (selectedTip.style.display === 'block') {
        selectedTip.style.display = 'none'; // If already open, close it
    } else {
        // Hide all tip details
        var tipDetails = document.querySelectorAll('.tip-details > div');
        tipDetails.forEach(function(detail) {
            detail.style.display = 'none';
        });
        
        // Show the selected tip details
        selectedTip.style.display = 'block';
    }
}

</script>

<script>
function showDetails(layer) {
    var details = getLayerDetails(layer);
    var layerDetails = document.getElementById('layer-details');
    var layers = document.getElementsByClassName('layer');

    // Reset color of all layers to original color
    for (var i = 0; i < layers.length; i++) {
        if (!layers[i].dataset.clicked) {
            layers[i].style.color = '#636363';
        }
    }

    // Toggle the display of layer details
    if (layerDetails.style.display === 'block') {
        layerDetails.style.display = 'none'; // Close the container if it's already open
    } else {
        // Display layer details
        layerDetails.innerHTML = details;
        layerDetails.style.display = 'block';
        // Change color of clicked layer to orange
        document.getElementById(layer).style.color = '#ff6600';
    }
}

function getLayerDetails(layer) {
    switch (layer) {
        case 'prevention':
            return '- <strong>Repair</strong> broken items<br>Opt for products with minimal packaging<br>- Use <strong>reusable bags/containers/water bottles</strong><br>- Plan meals and purchase only what is needed to reduce food waste';
        case 'reuse':
            return '- <strong>Donate</strong> or sell gently used items<br>- Repurpose items for new purposes or DIY projects<br>- Use <strong>durable and reusable</strong> products instead of single-use item';
        case 'recycling':
            return '- Support products made from recycled materials<br>- Support <strong><a href="https://shopunplug.com/blogs/sustainable-tips-guide/15-eco-friendly-products-in-malaysia" target="_blank">Eco-Friendly Products</a></strong><br>- Rinse and clean recyclable items before recycling<br>- Flatten cardboard boxes and crush aluminum cans to save space<br>- Understand recycling concept';
        case 'recovery':
            return '- Support initiatives for converting waste into energy<br>- <strong><a href="https://www.youtube.com/watch?v=mDIVpJgjoXQ" target="_blank">Compost food scraps and yard</a></strong> waste to create nutrient-rich soil<br>- Explore alternative energy sources (solar and wind power)<br>- Use biodegradable and compostable products whenever possible';
        case 'disposal':
            return '- Dispose of hazardous materials at designated collection sites<br>- Minimize waste sent to landfills by maximizing recycling and <strong><a href="https://lomi.com/blogs/news/compost-dos-and-donts" target="_blank">composting efforts</a></strong><br>- Use proper disposal methods for items such as pharmaceuticals and electronic waste';
        default:
            return 'No details available';
    }
}

// Change color of layers on hover and leave, and on click
var layers = document.getElementsByClassName('layer');
for (var i = 0; i < layers.length; i++) {
    layers[i].addEventListener('mouseenter', function() {
        if (!this.dataset.clicked) {
            this.style.color = '#ff6600'; // Change color to orange on hover
        }
    });
    layers[i].addEventListener('mouseleave', function() {
        if (!this.dataset.clicked) {
            this.style.color = '#636363'; // Revert color to original if not clicked
        }
    });
    layers[i].addEventListener('click', function() {
        for (var j = 0; j < layers.length; j++) {
            layers[j].dataset.clicked = false;
            layers[j].style.color = '#636363'; // Revert color of all layers to original
        }
        this.dataset.clicked = true;
        this.style.color = '#ff6600'; // Change color to orange on click
    });
}
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>