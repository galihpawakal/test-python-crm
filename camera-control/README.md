# Camera Control System

A Python-based camera control tool tailored for embedded systems and desktop environments. It allows live previewing of camera feeds, single photo capture, a dedicated burst capture mode, and real-time face detection.

## Setup & Installation

1. Create a Python virtual environment (optional but recommended).
2. Install the required dependencies:
   ```bash
   pip install -r requirements.txt
   ```

## Usage

1. Open `config.py` to adjust your baseline camera configurations, or leave them as default.
2. Run the main script:
   ```bash
   python main.py
   ```

### Key Mapping
- **`SPACE`**: Capture a single photo.
- **`B` (Hold)**: Continuous burst capture. Starts immediately when pressed and stops precisely when released.
- **`F`**: Toggle Real-time Face Detection on/off.
- **`S`**: Cycle camera resolution (e.g., 640x480 -> 1280x720 -> 1920x1080).
- **`O`**: Toggle Orientation (Landscape / Portrait).
- **`+` / `-`**: Increase / Decrease Shutter Speed (Exposure) live.
- **`[` / `]`**: Decrease / Increase ISO (Gain) live.
- **`R`**: Reset Exposure & ISO to the defaults specified in `config.py`.
- **`Q` or `ESC`**: Exit the application safely.

All captured photos will be saved inside the automatically generated `captures/` directory with a timestamp.

## Advanced Features
- **Face Detection:** Uses optimized Haar Cascades. Detection is processed on a low-resolution downscaled frame to maintain high FPS (even at 720p/1080p), and the green bounding boxes are mapped back to the UI. The boxes are only drawn on the preview, keeping your saved captures perfectly clean.
- **Hybrid Hardware/Software Exposure & ISO:** The script actively verifies if your UVC driver accepts the Exposure/Gain command via `cap.get()` every time you adjust it.
  - **[HW] Mode**: If accepted, the camera natively adjusts it.
  - **[SW SIM] Mode**: If rejected (which is common on Windows DSHOW), the system seamlessly kicks in a Software Simulation. It maps Shutter Speed to a brightness algorithm (`cv2.convertScaleAbs`), and maps ISO to an alpha multiplier mixed with a safe `np.clip` Gaussian noise generator to mimic high-ISO sensor grain. This simulation is applied directly to the raw frame, ensuring your preview perfectly matches your saved image!
- **Black Screen Prevention:** The script features an auto-recovery mechanism. If it detects that the raw frame is nearly completely black (underexposed), it will issue a warning in the console and automatically bump the gain/exposure slightly to prevent UI lockup.
