import cv2
import time
import threading
from pynput import keyboard
from config import CONFIG
from camera_controller import CameraController

# Global flags
is_bursting = False
burst_lock = threading.Lock()

def on_press(key):
    global is_bursting
    try:
        if hasattr(key, 'char') and key.char:
            if key.char.lower() == 'b':
                with burst_lock:
                    is_bursting = True
    except AttributeError:
        pass

def on_release(key):
    global is_bursting
    try:
        if hasattr(key, 'char') and key.char:
            if key.char.lower() == 'b':
                with burst_lock:
                    is_bursting = False
    except AttributeError:
        pass

def draw_overlay(frame, cam_controller, bursting, faces_count, fd_enabled):
    """Draws status text on the frame."""
    if frame is None:
        return frame
        
    w, h = cam_controller.get_actual_resolution()
    status_text = "BURST MODE" if bursting else "READY"
    color = (0, 0, 255) if bursting else (0, 255, 0) # BGR
    
    # Check Hardware vs SW simulation strings
    exp_mode = "[HW]" if cam_controller.hw_exposure_supported else "[SW SIM]"
    gain_mode = "[HW]" if cam_controller.hw_gain_supported else "[SW SIM]"
    
    fd_status = "ON" if fd_enabled else "OFF"
    
    # Texts
    texts = [
        f"Res: {w}x{h} | Status: {status_text}",
        f"Exp: {cam_controller.current_exposure:.1f} {exp_mode} | ISO: {cam_controller.current_gain:.1f} {gain_mode}",
        f"Face Detection: {fd_status} (Faces: {faces_count})",
        f"Photos Taken: {cam_controller.photo_count}",
        "[SPACE]: Cap  [B]: Burst  [F]: FaceDet  [S]: Scale  [O]: Orient  [+|-]: Exp  [ [|] ]: ISO  [R]: Reset  [Q]: Quit"
    ]
    
    y0, dy = 30, 30
    for i, line in enumerate(texts):
        y = y0 + i * dy
        # Draw background text for readability
        cv2.putText(frame, line, (10, y), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 0), 4)
        # Draw actual text
        cv2.putText(frame, line, (10, y), cv2.FONT_HERSHEY_SIMPLEX, 0.5, color if i == 0 else (0, 255, 0), 2)
        
    return frame

def main():
    global is_bursting
    
    cam = CameraController()
    if not cam.initialize():
        return
        
    # Start keyboard listener for burst mode
    listener = keyboard.Listener(on_press=on_press, on_release=on_release)
    listener.start()
    
    # Initialize Face Cascade
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    face_detection_enabled = CONFIG.FACE_DETECTION_ENABLED
    
    print("=== Camera Control Started ===")
    print("Press SPACE to take a single photo.")
    print("Hold 'B' to take burst photos.")
    print("Press 'F' to toggle Face Detection.")
    print("Press 'S' to cycle Resolutions.")
    print("Press 'O' to toggle Orientation (Landscape/Portrait).")
    print("Press '+' / '-' to adjust Exposure.")
    print("Press '[' / ']' to adjust ISO/Gain.")
    print("Press 'R' to reset Exposure & ISO to defaults.")
    print("Press 'Q' or 'ESC' in the window to exit.")
    
    last_burst_time = 0
    
    try:
        while True:
            ret, frame = cam.read_frame()
            if not ret or frame is None:
                print("Failed to grab frame. Exiting...")
                break
                
            with burst_lock:
                current_bursting = is_bursting
                
            # Handle Burst Capture
            if current_bursting:
                current_time = time.time() * 1000 # ms
                if current_time - last_burst_time >= CONFIG.BURST_INTERVAL_MS:
                    cam.capture_image(frame)
                    last_burst_time = current_time
                    
            # Copy frame for display (keep original clean for capture)
            display_frame = frame.copy()
            faces_count = 0
            
            # Face Detection
            if face_detection_enabled:
                target_w = 320
                scale_ratio = display_frame.shape[1] / target_w
                target_h = int(display_frame.shape[0] / scale_ratio)
                
                small_frame = cv2.resize(display_frame, (target_w, target_h))
                gray = cv2.cvtColor(small_frame, cv2.COLOR_BGR2GRAY)
                
                faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(20, 20))
                faces_count = len(faces)
                
                # Draw boxes scaled back to original resolution
                for (x, y, w, h) in faces:
                    x_orig = int(x * scale_ratio)
                    y_orig = int(y * scale_ratio)
                    w_orig = int(w * scale_ratio)
                    h_orig = int(h * scale_ratio)
                    
                    cv2.rectangle(display_frame, (x_orig, y_orig), (x_orig + w_orig, y_orig + h_orig), (0, 255, 0), 2)
                    cv2.putText(display_frame, 'Face', (x_orig, y_orig - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (0, 255, 0), 2)
            
            # Draw overlay
            display_frame = draw_overlay(display_frame, cam, current_bursting, faces_count, face_detection_enabled)
            
            # Show preview
            cv2.imshow('Camera Live Preview', display_frame)
            
            # Key polling for OpenCV window
            key = cv2.waitKey(1) & 0xFF
            
            if key == 27 or key == ord('q'): # ESC or Q
                print("Exiting...")
                break
            elif key == ord('f'): # Toggle face detection
                face_detection_enabled = not face_detection_enabled
                print(f"Face Detection {'ON' if face_detection_enabled else 'OFF'}")
            elif key == ord('s'): # Cycle Resolution
                cam.cycle_resolution()
            elif key == ord('o'): # Toggle Orientation
                cam.toggle_orientation()
            elif key == 32: # SPACE
                cam.capture_image(frame)
            elif key == ord('=') or key == ord('+'): # Increase Exposure
                cam.update_params(exposure=cam.current_exposure + 0.5)
            elif key == ord('-'): # Decrease Exposure
                cam.update_params(exposure=cam.current_exposure - 0.5)
            elif key == ord(']'): # Increase ISO
                cam.update_params(gain=cam.current_gain + 20.0)
            elif key == ord('['): # Decrease ISO
                cam.update_params(gain=cam.current_gain - 20.0)
            elif key == ord('r'): # Reset defaults
                cam.update_params(exposure=CONFIG.EXPOSURE, gain=CONFIG.GAIN)
                
    finally:
        listener.stop()
        cam.release()
        cv2.destroyAllWindows()

if __name__ == "__main__":
    main()
