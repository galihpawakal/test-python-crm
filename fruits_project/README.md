# YOLOv8 Fruits Detection

This project trains a YOLOv8 object detection model to detect and classify 9 different types of fruits:
Apple, Banana, Grapes, Kiwi, Mango, Orange, Pineapple, Sugerapple, Watermelon.

## Setup

1. Create a Python environment.
2. Install the dependencies:
   ```bash
   pip install -r requirements.txt
   ```
   *Note: If you have an NVIDIA GPU, make sure to install PyTorch with CUDA support first for faster training.*
3. Ensure the dataset is extracted into the `dataset/` directory.

## Usage

### Training
To train the model, run:
```bash
python train.py
```
This will train `yolov8n.pt` for 30 epochs (or the configured number of epochs). The best model will be saved in `runs/fruits_detection/weights/best.pt`.

### Inference
To run inference on the test images, run:
```bash
python inference.py
```
This script will open a window showing the bounding boxes, class labels, and confidence scores for each image. Press any key to move to the next image, or press `ESC` to stop.
