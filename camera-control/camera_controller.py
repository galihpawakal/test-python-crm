import os
import cv2
import time
import logging
import numpy as np
from datetime import datetime
from config import CONFIG

logging.basicConfig(level=logging.INFO, format='[%(asctime)s] %(levelname)s: %(message)s')

class CameraController:
    """Handles OpenCV camera initialization, configuration, and capture logic."""
    
    # Predefined resolutions to cycle through
    RESOLUTIONS = [
        (640, 480),
        (1280, 720),
        (1920, 1080)
    ]
    
    def __init__(self):
        self.cap = None
        self.photo_count = 0
        
        self.hw_exposure_supported = False
        self.hw_gain_supported = False
        
        self.current_exposure = CONFIG.EXPOSURE
        self.current_gain = CONFIG.GAIN
        
        self.is_portrait = False
        
        # Determine initial resolution index based on config
        self.res_index = 0
        for i, res in enumerate(self.RESOLUTIONS):
            if res[0] == CONFIG.WIDTH and res[1] == CONFIG.HEIGHT:
                self.res_index = i
                break
        
        # Ensure output directory exists
        CONFIG.OUTPUT_DIR.mkdir(parents=True, exist_ok=True)
        
        if os.name == 'nt':
            self.backend = cv2.CAP_DSHOW
            logging.info("Windows detected. Using cv2.CAP_DSHOW backend.")
        else:
            self.backend = cv2.CAP_V4L2
            logging.info("Linux/Unix detected. Using cv2.CAP_V4L2 backend.")
            
    def initialize(self) -> bool:
        """Initialize the camera and apply configurations."""
        self.cap = cv2.VideoCapture(CONFIG.CAMERA_INDEX, self.backend)
        
        if not self.cap.isOpened():
            logging.error(f"Failed to open camera index {CONFIG.CAMERA_INDEX}.")
            return False
            
        self.set_resolution(self.RESOLUTIONS[self.res_index][0], self.RESOLUTIONS[self.res_index][1])
        self.update_params(self.current_exposure, self.current_gain)
        logging.info("Camera initialized successfully.")
        return True
        
    def set_resolution(self, width, height):
        """Sets the camera resolution."""
        if self.cap:
            self.cap.set(cv2.CAP_PROP_FRAME_WIDTH, width)
            self.cap.set(cv2.CAP_PROP_FRAME_HEIGHT, height)
            logging.info(f"Resolution set to {width}x{height}")
            
    def toggle_orientation(self):
        """Toggles between landscape and portrait orientation."""
        self.is_portrait = not self.is_portrait
        logging.info(f"Orientation set to {'Portrait' if self.is_portrait else 'Landscape'}")
            
    def cycle_resolution(self):
        """Cycles to the next predefined resolution and re-applies HW settings."""
        self.res_index = (self.res_index + 1) % len(self.RESOLUTIONS)
        new_w, new_h = self.RESOLUTIONS[self.res_index]
        
        self.set_resolution(new_w, new_h)
        # Re-apply exposure and gain as resolution changes might reset the camera's internal state
        time.sleep(0.1) # Small delay to let the resolution change settle
        self.update_params(self.current_exposure, self.current_gain)
        
    def update_params(self, exposure=None, gain=None):
        """Update and clamp parameters, re-verifying hardware support."""
        if exposure is not None:
            # Clamp exposure between -10.0 and 0.0
            self.current_exposure = max(-10.0, min(0.0, float(exposure)))
            
            auto_exp_val = 1 if self.backend == cv2.CAP_V4L2 else 0
            self.cap.set(cv2.CAP_PROP_AUTO_EXPOSURE, auto_exp_val)
            time.sleep(0.05)
            
            before = self.cap.get(cv2.CAP_PROP_EXPOSURE)
            self.cap.set(cv2.CAP_PROP_EXPOSURE, self.current_exposure)
            time.sleep(0.05)
            after = self.cap.get(cv2.CAP_PROP_EXPOSURE)
            
            if after == self.current_exposure or after != before:
                self.hw_exposure_supported = True
                logging.info(f"[HARDWARE OK] Exposure set to {after}")
            else:
                self.hw_exposure_supported = False
                logging.warning(f"[HARDWARE UNSUPPORTED] Exposure fallback to software simulation.")

        if gain is not None:
            # Clamp gain between 0.0 and 400.0
            self.current_gain = max(0.0, min(400.0, float(gain)))
            
            before = self.cap.get(cv2.CAP_PROP_GAIN)
            self.cap.set(cv2.CAP_PROP_GAIN, self.current_gain)
            time.sleep(0.05)
            after = self.cap.get(cv2.CAP_PROP_GAIN)
            
            if after == self.current_gain or after != before:
                self.hw_gain_supported = True
                logging.info(f"[HARDWARE OK] Gain set to {after}")
            else:
                self.hw_gain_supported = False
                logging.warning(f"[HARDWARE UNSUPPORTED] Gain fallback to software simulation.")

    def _simulate_effects(self, frame):
        """Simulates shutter speed (brightness) and ISO (grain + contrast) safely."""
        if frame is None:
            return None
            
        if self.hw_exposure_supported and self.hw_gain_supported:
            return frame # No simulation needed
            
        alpha = 1.0
        beta = 0.0
        
        # Simulate Exposure
        if not self.hw_exposure_supported:
            shift = (self.current_exposure + 6.0) * 20.0
            beta += shift
            
        # Simulate Gain/ISO 
        noise_std = 0.0
        if not self.hw_gain_supported:
            alpha *= max(0.1, self.current_gain / 100.0)
            if self.current_gain > 100.0:
                noise_std = (self.current_gain - 100.0) / 10.0
                
        # Apply Brightness and Contrast
        if alpha != 1.0 or beta != 0.0:
            frame = cv2.convertScaleAbs(frame, alpha=alpha, beta=beta)
            
        # Apply synthetic noise if ISO is high
        if noise_std > 0:
            noise = np.random.normal(0, noise_std, frame.shape)
            noisy_frame = frame.astype(np.int16) + noise
            frame = np.clip(noisy_frame, 0, 255).astype(np.uint8)
            
        return frame

    def read_frame(self):
        """Read a single frame from the camera and apply effects."""
        if not self.cap or not self.cap.isOpened():
            return False, None
            
        ret, frame = self.cap.read()
        if ret and frame is not None:
            # Handle orientation via Center Crop (Portrait)
            if self.is_portrait:
                H, W = frame.shape[:2]
                target_W = int(H * (H / W)) if W > 0 else H
                start_x = (W - target_W) // 2
                frame = frame[:, start_x:start_x + target_W]
                
            # Apply hardware fallback effects so capture matches preview
            frame = self._simulate_effects(frame)
            
            # Safety check for nearly black frames
            mean_val = frame.mean()
            if mean_val < 10.0:
                logging.warning("[WARNING] Frame appears nearly black, check exposure/ISO config")
                # Auto-recovery: bump gain slightly so it's not totally stuck
                # But don't bump above 400
                if self.current_gain < 400.0:
                    self.update_params(gain=self.current_gain + 20.0)
                elif self.current_exposure < -1.0:
                    self.update_params(exposure=self.current_exposure + 1.0)
                
        return ret, frame
        
    def capture_image(self, frame) -> bool:
        """Save the provided frame to the captures directory."""
        if frame is None:
            logging.error("Capture failed: No frame provided.")
            return False
            
        timestamp = datetime.now().strftime("%Y%m%d_%H%M%S_%f")[:19]
        filename = f"capture_{timestamp}.jpg"
        filepath = CONFIG.OUTPUT_DIR / filename
        
        success = cv2.imwrite(str(filepath), frame)
        if success:
            self.photo_count += 1
            logging.info(f"Saved {filename}")
        else:
            logging.error(f"Failed to save {filename}")
            
        return success

    def get_actual_resolution(self):
        """Return the actual resolution being captured."""
        if not self.cap:
            return 0, 0
        w = int(self.cap.get(cv2.CAP_PROP_FRAME_WIDTH))
        h = int(self.cap.get(cv2.CAP_PROP_FRAME_HEIGHT))
        if self.is_portrait:
            target_w = int(h * (h / w)) if w > 0 else h
            return target_w, h
        return w, h
        
    def release(self):
        """Release camera resources."""
        if self.cap:
            self.cap.release()
            logging.info("Camera released.")
