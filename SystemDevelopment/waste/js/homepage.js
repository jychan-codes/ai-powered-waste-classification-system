
function hoverEffect(element, hoverImagePath, recyclable, otherExamples) {
    element.style.zIndex = 1; /* Bring the hovered element to the front */
    
    // Show the hover image
    element.querySelector('.hover-image').style.display = 'block';
    // Hide the original image
    element.querySelector('img').style.display = 'none';
    
    // Create recyclable or non-recyclable text element
    var textElement = document.createElement("p");
    textElement.style.fontSize = "9px"; // Set font size
    textElement.style.fontWeight = "bold"; // Make the font bold
    
    // Set the text content based on recyclable status
    if (recyclable === "Yes") {
        textElement.innerHTML = "Recyclable";
    } else {
        textElement.innerHTML = "Non-Recyclable";
    }
    
    // Append the text element to the plastic type container
    element.appendChild(textElement);
    
    // Add other examples if available
    if (otherExamples) {
        var otherExamplesList = otherExamples.split(', '); // Split the examples by comma and space
        var otherExamplesElement = document.createElement("ul");
        otherExamplesElement.style.fontSize = "7px"; // Set font size
        otherExamplesElement.style.paddingLeft = "0"; // Remove default padding
        otherExamplesList.forEach(function(example) {
            var listItem = document.createElement("li");
            listItem.textContent = example;
            listItem.style.listStyleType = "none"; // Remove bullet points
            otherExamplesElement.appendChild(listItem);
        });
        element.appendChild(otherExamplesElement);
    }
}

function resetImage(element, originalImagePath) {
    element.style.zIndex = 0; /* Reset z-index when not hovered */
    
    // Hide the hover image
    element.querySelector('.hover-image').style.display = 'none';
    // Show the original image
    element.querySelector('img').style.display = 'block';
    
    // Remove any previously added recyclable or non-recyclable text
    var textElement = element.querySelector('p');
    if (textElement) {
        element.removeChild(textElement);
    }
    // Remove any previously added other examples list
    var otherExamplesElement = element.querySelector('ul');
    if (otherExamplesElement) {
        element.removeChild(otherExamplesElement);
    }
}







    document.addEventListener('DOMContentLoaded', function () {
        // Get the canvas element
        var ewasteCanvas = document.getElementById('ewasteChart').getContext('2d');

        // Original data (in percentage)
        var data = [32, 24];

        // Create the pie chart
        var wasteCompositionPieChart = new Chart(ewasteCanvas, {
            type: 'pie',
            data: {
                labels: ['Small Equipment (laptop, phone)', 'Large Equipment (washing machine)'],
                datasets: [{
                    data: data,
                    backgroundColor: ['#203E32', '#F2F3EE'],
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
                            // Display the value on each pie segment as a percentage
                            return value + '%';
                        },
                    }
                }
            }
        });
   });



