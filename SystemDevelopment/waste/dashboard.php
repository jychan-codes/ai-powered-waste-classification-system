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
$greeting = isset($_SESSION['username']) ? "Hi, " . $_SESSION['username'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Wisdom</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=YourSelectedFont&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <script src="js/dashboard.js"></script>
     <!-- Include Chart.js -->
     <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
     <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
     <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" />
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


<div class="container mt-5" style="margin-top: 80px; padding-top: 50px; ">
    <?php
    if (isset($_SESSION['username'])) {
        // User is logged in, display user information and order history
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        echo "<h1>$username, Waste Dashboard!</h1>";
    ?>

<?php
 
// Data for Malaysia Landfill Status
// Data are from Literature Review
$dataPoints = array( 
	array("y" => 3,"label" => "Perlis" ),
	array("y" => 5,"label" => "Kedah" ),
	array("y" => 14,"label" => "Penang" ),
	array("y" => 11,"label" => "Perak" ),
	array("y" => 30,"label" => "Selangor" ),
    array("y" => 12,"label" => "Kuala Lumpur" ),
    array("y" => 6,"label" => "Melaka" ),
    array("y" => 15,"label" => "Johor" ),
    array("y" => 4,"label" => "Pahang" ),
    array("y" => 6,"label" => "Negeri Sembilan" ),
    array("y" => 3,"label" => "Terengganu" ),
    array("y" => 5,"label" => "Kelantan" ),
    array("y" => 5,"label" => "Sarawak" ),
    array("y" => 5,"label" => "Sabah" ),
);
?>

<div class="dashboard-container">
        <div class="dashboard-box">
            <h4>Global Waste Generation</h4>
            <label for="continentSelector">Select Continent: </label>
            <select id="continentSelector" onchange="updateWasteMap()">
                <option value="Asia">Asia</option>
                <option value="Africa">Africa</option>
                <option value="North America">North America</option>
                <option value="South America">South America</option>
                <option value="Europe">Europe</option>
                <option value="Australia">Australia</option>
            </select>
            <label for="timelineSlider">Select Timeline (2020-2023): </label>
            <input type="range" id="timelineSlider" min="2020" max="2023" step="1" value="2020" oninput="updateWasteMap()">
            <span id="currentYear">2020</span>
            <div id="map" style="width: 100%; height: 400px;"></div>
        </div>
        
        <div class="dashboard-box">
            <h4>E-Waste Collection Centers</h4>
            <p>by States</p>
            <canvas id="ewasteCollectionChart" width="10" height="10"></canvas>
            <p>Total E-Waste Centers: <span id="energyValue">121</span></p>
            <div style="display: flex; justify-content: center;">
                <p style="color: #0066cc; margin-right: 10px; text-decoration: none; font-weight: bold;"> <a href="https://www.doe.gov.my/wp-content/uploads/2021/07/LIST-OF-E-WASTE-COLLECTION-CENTERS-IN-MALAYSIA.pdf" 
                target="_blank">Summary</a></p>
          
                <p style="color: #0066cc; margin-left: 10px; text-decoration: none; font-weight: bold;"> 
                <a href="https://www.google.com/maps/d/u/0/embed?mid=1Kt20cFEZtXwO07l7YwQf_HCVuSPykUo&ehbc=2E312F&noprof=1" target=\"_blank\" class=\"recycling-center-link\">Map</a></p>
            </div>
        </div>
        

        <div class="dashboard-box" id="recyclingBox">
            <h4>Household Waste Composition (2023)</h4>
            <canvas id="wasteCompositionChart" width="10" height="10"></canvas>
        </div>
</div>

<!-- New row for Recycling Rate -->
<div class="row mt-4">
                <div class="col">
                    <div class="dashboard-box">
                        <h4>Recycling Rate (2013-2023)</h4>
                        <label for="countrySelect">Select Country:</label>
                        <select id="countrySelect" onchange="updateRecyclingRate()">
                        <option value="malaysia" selected>Malaysia</option>
                        <option value="singapore">Singapore</option>
                        <option value="taiwan">Taiwan</option>
                    </select>
                        <canvas id="recyclingRateChart" width="400" height="300"></canvas>
                    </div>
                </div>

                <div class="dashboard-box">
                    <h4>Malaysia Landfills Status</h4>
                    <label for="stateSelector">Select State: </label>
                    <select id="stateSelector" onchange="updateLandfillChart()">
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Pulau Pinang">Pulau Pinang</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Labuan">Labuan</option>
                    </select>
                <canvas id="landfillChart" width="400" height="300"></canvas>
            </div>
    </div>
</div>

<!-- Script for Global Waste Generation -->
<script>
    // Function to update the selected year and trigger map update
    function updateWasteMap() {
        var slider = document.getElementById("timelineSlider");
        var currentYearElement = document.getElementById("currentYear");
        var selectedYear = slider.value;

        currentYearElement.innerHTML = selectedYear;

        // Call the function to update the map with the selected year
        updateMapWithYear(selectedYear);
    }

    // Function to update the map based on the selected year
    function updateMapWithYear(selectedYear) {
        console.log('Selected Year:', selectedYear);
    }

    // Mapbox access token
    mapboxgl.accessToken = 'pk.eyJ1IjoiY2hhbjIwMDIiLCJhIjoiY2xyaHF2Ymg5MDF5dzJpb3NrN290ZzhyaCJ9.VldGMCao2PUi50LHkA8TLQ';

    // Waste generation data
    // 1 ton = 1,000 KG (around 2000 pounds)
    var wasteData = {
        "Asia": {
            2020: "1.1 trillion tons (1 ton = 1,000 KG)",
            2021: "More than 800 million tons (1 ton = 1,000 KG)",
            2022: 800000,
            2023: 120000,
        },
        "Africa": {
            2020: 50000,
            2021: 400000,
            2022: 800000,
            2023: 55000,
        },
        
        "North America": {
            2020: ">300 million tonnes of waste (1 ton = 1,000 KG)",
            2021: 400000,
            2022: 800000,
            2023: 55000,
        },
        "South America": {
            2020: ">100 million tonnes of waste (1 ton = 1,000 KG)",
            2021: 400000,
            2022: 800000,
            2023: "Data Not Available",
            
        },
        "Europe": {
            2020: "More than 2.1 billion tonnes",
            2021: "237.5 million metric tons (1 metric ton = 1,000 KG)",
            2022: "229.5 million metric tons (1 metric ton = 1,000 KG)",
            2023: "2.2 billion tonnes of waste",
            
        },
        "Australia": {
            2020: "75.8 million tonnes (equivalent to 2.95 tonnes per person > 1 ton = 1,000 KG)",
            2021: "75.8 million tonnes (equivalent to 2.95 tonnes per person > 1 ton = 1,000 KG)",
            2022: "Around 540kg of household waste per person each year",
            2023: "75.8 mega tonnes of waste", 
        },
        
    };
    // Create a map
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [10, 0], 
        zoom: 2 
    });

    // Listen for the "input" event on the timeline slider
    document.getElementById("timelineSlider").addEventListener("input", function() {
        // Update the displayed year as the slider changes
        var slider = document.getElementById("timelineSlider");
        var currentYearElement = document.getElementById("currentYear");
        currentYearElement.innerHTML = slider.value;

        // Call the function to update the map with the selected year
        updateMapWithYear(slider.value);
    });

    // Function to update the map based on the selected continent and year
    function updateWasteMap() {
        var selectedContinent = document.getElementById("continentSelector").value;
        var selectedYear = document.getElementById("timelineSlider").value;

        var wasteAmount = wasteData[selectedContinent][selectedYear];

        // Update the map center based on the selected continent
        var continentCenters = {
            "Asia": [90, 30],
            "Africa": [17, -3],
            "North America": [-100, 40],
            "South America": [-60, -15],
            "Europe": [15, 50],
            "Australia": [135, -25],
            
        };

        map.flyTo({
            center: continentCenters[selectedContinent],
            zoom: 2.5,
            essential: true
        });

        // Remove existing markers
        if (map.getLayer('waste-marker')) {
            map.removeLayer('waste-marker');
            map.removeSource('waste-marker-source');
        }

        // Add a marker with a popup showing waste generation information
        map.addSource('waste-marker-source', {
            type: 'geojson',
            data: {
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: continentCenters[selectedContinent]
                }
            }
        });

        map.addLayer({
            id: 'waste-marker',
            type: 'circle',
            source: 'waste-marker-source',
            paint: {
                'circle-radius': 10,
                'circle-color': '#FF0000'
            }
        });

        map.on('click', 'waste-marker', function (e) {
            var popup = new mapboxgl.Popup()
                .setLngLat(e.features[0].geometry.coordinates)
                .setHTML(`<p>Waste Amount (${selectedYear}): ${wasteAmount} tonnes</p>`)
                .addTo(map);
        });

        // Enable the map to trigger the click event on the marker
        map.on('mouseenter', 'waste-marker', function () {
            map.getCanvas().style.cursor = 'pointer';
        });

        map.on('mouseleave', 'waste-marker', function () {
            map.getCanvas().style.cursor = '';
        });
    }

    // Listen for a change event on the timeline slider
    document.getElementById("timelineSlider").addEventListener("input", function() {
        // Update the map when the timeline changes
        updateWasteMap();
    });

    // Initial map setup
    updateWasteMap();
</script>


<!-- Script for E-Waste Collection Points -->
<script>
    // E-Waste Collection Points Data
    var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;

    // Extract labels and values from data
    var labels = dataPoints.map(point => point.label);
    var values = dataPoints.map(point => point.y);

    // Define a nice color palette
    var colorPalette = ['#d0d4ce', '#95bb9d', '#5e8478', '#3e6c6c', '#2e4c5c',
    '#a1a6a4', '#75987c', '#4f6f68', '#3b5757', '#2b424c', '#8d978f', '#98ac98', '#879c86', '#b7c9af'];

    // Get the canvas element
    var ctx = document.getElementById('ewasteCollectionChart').getContext('2d');

    // Create a bar chart (formerly horizontalBar)
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Collection Points',
                data: values,
                backgroundColor: colorPalette,
             
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>


<!-- Script for Household Waste Composition 2023-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the canvas element
        var wasteCompositionCanvas = document.getElementById('wasteCompositionChart').getContext('2d');

        // Initial data
        var data = [36, 24, 9, 2, 2, 4, 23];

        // Calculate the total sum of data
        var total = data.reduce((acc, value) => acc + value, 0);

        // Calculate the percentage values
        var percentages = data.map(value => ((value / total) * 100).toFixed(2));

        // Create the pie chart
        var wasteCompositionPieChart = new Chart(wasteCompositionCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Food Waste', 'Plastic', 'Paper', 'Metal', 'Glass', 'Garden', 'Others (textiles, leather, rubber, diaper, etc)' ],
                datasets: [{
                    data: percentages, 
                    backgroundColor: ['#485965', '#c1d0b5', '#dce3c7','#dbead2', '#fff8de', '#fff4e9', '#ccc6b1'],
                }]
            },
            options: {
                responsive: true,
                
                plugins: {
                    datalabels: {
                        color: '#fff', // Label text color
                        anchor: 'end',
                        align: 'start',
                        offset: 4,
                        formatter: (value, context) => {
                            // Display the value on each pie segment
                            return value + '%';
                        },
                    }
                }
            }
        });
   });
</script>



<!-- Script for Recycling Rate Dashboard -->
<script>
    // Recycling rate data 
    var malaysiaRecyclingRateData = [
        { year: 2013, rate: 10.5 },
        { year: 2014, rate: 13.2 },
        { year: 2015, rate: 15.7 },
        { year: 2016, rate: 17.5 },
        { year: 2017, rate: 21.0 },
        { year: 2018, rate: 24.6 },
        { year: 2019, rate: 28.1 },
        { year: 2020, rate: 30.7 },
        { year: 2021, rate: 31.5 },
        { year: 2022, rate: 33.17 },
        { year: 2023, rate: 35.38 },
    ];

    var singaporeRecyclingRateData = [
        // Recycling rate data for the selected other country
        // Include data for each year from 2013 to 2024
        { year: 2013, rate: 61 },
        { year: 2014, rate: 60 },
        { year: 2015, rate: 61 },
        { year: 2016, rate: 61 },
        { year: 2017, rate: 61 },
        { year: 2018, rate: 61 },
        { year: 2019, rate: 59 },
        { year: 2020, rate: 52 },
        { year: 2021, rate: 55},
        { year: 2022, rate: 57 },
    ];

    var taiwanRecyclingRateData = [
        // Recycling rate data for the selected other country
        // Include data for each year from 2013 to 2024
        { year: 2013, rate: 42},
        { year: 2014, rate: 43.03 },
        { year: 2015, rate: 55 },
        { year: 2016, rate: 55},
        { year: 2017, rate: 52.39 },
        { year: 2018, rate: 55},
        { year: 2019, rate: 58 },
        { year: 2020, rate: 58.8 },
        { year: 2021, rate: 58.8},
        { year: 2022, rate: 59.5 },
        { year: 2023, rate: 61 },
    ];

    // Initial data for Malaysia
    var currentCountry = 'malaysia';
    var currentRecyclingRateData = malaysiaRecyclingRateData;

    // Function to update recycling rate chart based on the selected country
    function updateRecyclingRate() {
        var selectedCountry = document.getElementById('countrySelect').value;

        // Update current data based on the selected country
        if (selectedCountry === 'malaysia') {
            currentRecyclingRateData = malaysiaRecyclingRateData;
        } else if (selectedCountry === 'singapore') {
            currentRecyclingRateData = singaporeRecyclingRateData;
        }else if (selectedCountry === 'taiwan') {
            currentRecyclingRateData = taiwanRecyclingRateData;
        }
        
        // Update the chart
        recyclingRateChart.data.labels = currentRecyclingRateData.map(entry => entry.year);
        recyclingRateChart.data.datasets[0].data = currentRecyclingRateData.map(entry => entry.rate);
        recyclingRateChart.update();
    }

    // Get the canvas element
    var ctx = document.getElementById('recyclingRateChart').getContext('2d');

    // Create a line chart (initially for Malaysia)
    var recyclingRateChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: malaysiaRecyclingRateData.map(entry => entry.year),
            datasets: [{
                label: 'Recycling Rate (%)',
                data: malaysiaRecyclingRateData.map(entry => entry.rate),
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false,
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    title: {
                        display: true,
                        text: 'Year'
                    }
                },
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Recycling Rate (%)'
                    }
                    }
                },
                plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    backgroundColor: 'rgba(255, 255, 255, 0.8)',
                    borderRadius: 4,
                    display: 'auto',
                    formatter: (value, context) => value.toFixed(2) + '%', // Format value as percentage
                }

            }
        }
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script for Malaysia Landfill -->
<script>
    // Data
    var landfillData = {
        "Johor": [13, 21],
        "Kedah": [10, 5],
        "Kelantan": [13, 4],
        "Melaka": [2, 5],
        "Negeri Sembilan": [8, 10],
        "Pahang": [19, 13],
        "Perak": [20, 9],
        "Perlis": [1, 1],
        "Pulau Pinang": [1, 2],
        "Sabah": [21, 1],
        "Sarawak": [51, 12],
        "Selangor": [6, 12],
        "Terengganu": [9, 12],
        "Kuala Lumpur": [1, 7],
        "Labuan": [1, 0],
        "Putrajaya": [0, 0],
        "Total": [176, 114],
    };

    // Initial state
    var selectedState = "Johor";

    // Function to update the chart based on the selected state
    function updateLandfillChart() {
        selectedState = document.getElementById("stateSelector").value;
        var chartData = landfillData[selectedState];

        // Destroy previous chart instance to avoid overlapping
        if (window.myChart) {
            window.myChart.destroy();
        }

        var ctx = document.getElementById("landfillChart").getContext("2d");
        window.myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Landfills in Operation', 'Landfills not in Operation'],
                datasets: [{
                    label: selectedState,
                    data: chartData,
                    backgroundColor: [
                        'rgba(225,190,190, 0.5)',
                        'rgba(187,206,178, 0.5)',
                    ],
                    borderColor: [
                        'rgba(225,190,190, 1)',
                        'rgba(187,206,178, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Initial chart setup
    updateLandfillChart();
</script>


<?php
}else {
    // User is not logged in
    echo "<h1>Your Dashboard</h1>";
    echo "<p>You are not logged in. Please log in to access your dashboard.</p>";
 }
 ?>

</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>