# SoundScape

A web application for an immersive audio experience based on location.

## 🚀 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Make sure you have the following installed on your system:
- PHP (version specified in `composer.json`)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) and npm

### Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/haroonsiddiqqureshi/SoundScape.git
    ```

2.  **Navigate to the project directory:**
    ```bash
    cd SoundScape
    ```

3.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

4.  **Install JavaScript dependencies:**
    ```bash
    npm install
    ```

5.  **Set up your environment file:**
    Copy the `.env.example` file to a new file named `.env`.
    ```bash
    cp .env.example .env
    ```
    Then, generate your application key.
    ```bash
    php artisan key:generate
    ```
    *Update the `.env` file with your database credentials and other environment-specific settings.*

6.  **Build frontend assets:**
    ```bash
    npm run build
    ```

### Running the Application

1.  **Start the Laravel development server:**
    ```bash
    php artisan serve
    ```

2.  **Start the Vite development server (in a new terminal):**
    ```bash
    npm run dev
    ```

3.  **Access the application:**
    Open your web browser and navigate to:
    ```
    http://127.0.0.1:8000
    ```