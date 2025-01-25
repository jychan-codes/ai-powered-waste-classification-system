from flask import Flask, request, jsonify
from tensorflow.keras.models import load_model
from tensorflow.keras.preprocessing import image
import numpy as np
import io

app = Flask(__name__)

# Load the updated model
#CNN model trained by using sctrach
#model = load_model("C:/xampp/htdocs/waste/trainedModel/waste_classification_model_version14.keras")

#CNN model trained by using pre-trained VGG16 as base architecture
model = load_model("C:/xampp/htdocs/waste/trainedModel/waste_classification_model_version15.keras")

# Define class labels
class_labels = ['battery', 'cardboard', 'charger', 'glass', 
'laptop', 'metal', 'mouse', 'organic', 'paper', 'plastic', 'smartphone', 'trash']

# Define a route for the prediction endpoint
@app.route('/predict', methods=['POST'])
def predict():
    try:
        # Get the uploaded file from the request
        file = request.files['file']

        # Read the content of the file into a BytesIO object
        file_content = file.read()
        img = image.load_img(io.BytesIO(file_content), target_size=(128, 128))

        # Preprocess the image
        img_array = image.img_to_array(img)
        img_array = np.expand_dims(img_array, axis=0) / 255.0  # Normalize the image

        # Make predictions
        predictions = model.predict(img_array)

        # Get the class with the highest probability
        predicted_class = np.argmax(predictions, axis=1)[0]

        # Get the class name based on the index
        predicted_label = class_labels[predicted_class]

        # Get the confidence score for the predicted class
        confidence_score = predictions[0][predicted_class]

        # Prepare the response
        response = {
            "prediction": predicted_label,
            "confidence": float(confidence_score),
         
        }

        return jsonify(response)

    except Exception as e:
        #return jsonify({"error": str(e)})

        

        return jsonify({"error": "An error occurred during prediction."})

if __name__ == '__main__':
    app.run(debug=True)
