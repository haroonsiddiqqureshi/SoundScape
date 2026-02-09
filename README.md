# SoundScape

A web application for an immersive audio experience based on location, built with Laravel, Vue.js, and Inertia.js.

## ✨ Core Technologies

-   **Backend**: Laravel 12
-   **Frontend**: Vue.js 3 & Vite
-   **Styling**: Tailwind CSS
-   **Scaffolding**: Laravel Jetstream
-   **Adapter**: Inertia.js

## 🚀 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

Make sure you have the following installed on your system:
-   PHP (^8.2)
-   [Composer](https://getcomposer.org/)
-   [Node.js](https://nodejs.org/) and npm

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

    *For Windows:*
    ```bash
    copy .env.example .env
    ```
    *For macOS/Linux:*
    ```bash
    cp .env.example .env
    ```

6.  **Generate your application key:**
    ```bash
    php artisan key:generate
    ```

7.  **Configure your `.env` file:**
    Open the `.env` file and update the database credentials (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) and other environment-specific settings.

8.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

### Running the Development Servers

This project includes a convenient script to run all necessary development servers concurrently.

1.  **Start the servers (Laravel, Vite, and Queue):**
    ```bash
    composer run dev
    ```
    This command will start the PHP development server, the Vite server for hot-reloading, and the queue listener.

2.  **Access the application:**
    Open your web browser and navigate to:
    ```
    http://127.0.0.1:8000
    ```

### Building for Production

To compile and bundle your assets for production, run the following command:
```bash
npm run build
```
