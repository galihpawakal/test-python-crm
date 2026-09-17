from dataclasses import dataclass
from pathlib import Path

@dataclass
class Config:
    """Configuration parameters for the Camera Control System."""
    CAMERA_INDEX: int = 0
    
    WIDTH: int = 1280
    HEIGHT: int = 720
    
    # Exposure: Default to -1.0 (Much brighter for most webcams)
    EXPOSURE: float = -1.0 
    
    # Gain/ISO: Default to 150.0 
    GAIN: float = 150.0     
    
    BURST_INTERVAL_MS: int = 150
    OUTPUT_DIR: Path = Path(__file__).resolve().parent / "captures"
    
    # Face Detection defaults
    FACE_DETECTION_ENABLED: bool = False

CONFIG = Config()
