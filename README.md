# Technical Assignment Repository

This repository contains all the technical assignment projects, separated into their respective directories. 

## Project Structure

- [**ci_cms**](./ci_cms): A CodeIgniter 4 based Content Management System (CMS) with an E-commerce simulation feature, including dynamic shopping cart, simulated payment gateway, and printable receipt.
- [**camera-control**](./camera-control): A Python-based script for interacting with and controlling cameras using OpenCV.
- [**fruits_project**](./fruits_project): A Machine Learning/Computer Vision project for fruit detection using YOLOv8.

## Installation & Usage

### 1. CI CMS (`ci_cms`)
- **Requirements**: PHP 8.x, Composer, MySQL/MariaDB
- **Setup**:
  1. Navigate to `ci_cms/` directory.
  2. Run `composer install` to install dependencies.
  3. Copy `env` to `.env` and configure your database settings.
  4. Run migrations: `php spark migrate`
  5. Run the development server: `php spark serve`
  6. Access via `http://localhost:8080`.

### 2. Camera Control (`camera-control`)
- **Requirements**: Python 3.x
- **Setup**:
  1. Navigate to `camera-control/` directory.
  2. Create a virtual environment: `python -m venv venv`
  3. Activate it: `venv\Scripts\activate` (Windows)
  4. Install dependencies: `pip install -r requirements.txt`
  5. Run the application: `python main.py`

### 3. Fruits Detection Project (`fruits_project`)
- **Requirements**: Python 3.x
- **Setup**:
  1. Navigate to `fruits_project/` directory.
  2. Create a virtual environment: `python -m venv venv`
  3. Activate it: `venv\Scripts\activate` (Windows)
  4. Install dependencies: `pip install -r requirements.txt`
  5. Run training/inference as documented in the folder's README.

## System Specifications

The projects were developed and tested on the following environment:
- **Operating System**: Windows 11
- **Tools**: PHP 8.1+, Composer, Python 3.10+, CodeIgniter 4, YOLOv8, OpenCV
