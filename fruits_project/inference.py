import os
import cv2
from pathlib import Path
from ultralytics import YOLO

def run_inference():
    base_dir = Path(__file__).resolve().parent
    model_path = base_dir / "runs" / "fruits_detection" / "weights" / "best.pt"
    test_path = base_dir / "dataset" / "test" / "images"
    
    if not model_path.exists():
        print(f"Model not found at: {model_path}")
        print("Please run train.py first to generate the model.")
        return
        
    if not test_path.exists():
        print(f"Test path not found at: {test_path}")
        return
        
    print(f"Loading model from: {model_path}")
    model = YOLO(str(model_path))
    
    image_paths = []
    if test_path.is_file():
        image_paths.append(test_path)
    elif test_path.is_dir():
        for ext in ['.jpg', '.jpeg', '.png']:
            image_paths.extend(test_path.rglob(f'*{ext}'))
    
    if not image_paths:
        print(f"No images found in {test_path}")
        return
        
    print(f"Found {len(image_paths)} images. Starting inference...")
    
    for img_path in image_paths:
        print(f"Processing: {img_path.name}")
        results = model(str(img_path), conf=0.5, iou=0.45) # Meningkatkan conf dan mengatur iou untuk mengurangi deteksi ganda
        
        # Plot the results
        annotated_frame = results[0].plot()
        
        # Show image
        cv2.imshow("YOLOv8 Inference", annotated_frame)
        
        # Wait for key press (0 = wait indefinitely until key is pressed)
        print("Press any key to see the next image, or close the window (Press ESC to exit).")
        key = cv2.waitKey(0)
        
        # If user presses ESC (27), break the loop
        if key == 27:
            print("Inference stopped by user.")
            break
            
    cv2.destroyAllWindows()
    print("Inference complete.")

if __name__ == "__main__":
    run_inference()
