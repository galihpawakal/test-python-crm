import os
from pathlib import Path
from ultralytics import YOLO

def main():
    # Define absolute paths using pathlib
    base_dir = Path(__file__).resolve().parent
    data_yaml_path = base_dir / "dataset" / "data.yaml"
    
    print(f"Using data configuration at: {data_yaml_path}")
    
    if not data_yaml_path.exists():
        print(f"Error: {data_yaml_path} does not exist!")
        return

    # Initialize model (automatically defaults to task='detect' for yolov8n.pt)
    print("Loading YOLOv8n model...")
    model = YOLO('yolov8n.pt')

    # Train the model
    # Note: epochs=3 is for smoke test. Change to epochs=30 for full training later.
    print("Starting training...")
    results = model.train(
        data=str(data_yaml_path),
        epochs=100, 
        imgsz=640,
        batch=8,
        device='', # Auto-detect (uses GPU if available, else CPU)
        patience=10,
        project=str(base_dir / "runs"),
        name="fruits_detection",
        exist_ok=True # Overwrite if folder exists for repeated testing
    )

    print("Training complete.")
    
    # Print path to best.pt
    # In YOLOv8, results object or model itself keeps track of save_dir
    best_model_path = Path(model.trainer.save_dir) / "weights" / "best.pt" if hasattr(model, 'trainer') and model.trainer else base_dir / "runs" / "fruits_detection" / "weights" / "best.pt"
    
    print(f"\nAbsolute path to best model (best.pt):")
    print(best_model_path.absolute())

if __name__ == "__main__":
    main()
