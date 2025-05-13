# PHP ZIP Codes Project

A PHP application for handling ZIP code validation with external API integration.

## Requirements

- PHP >= 7.4
- Composer (PHP package manager)
- Web server (Apache/Nginx) or PHP's built-in server

## Installation

1. Clone the repository:

```bash
git clone [your-repository-url]
cd php-zip-zodes
```

2. Install dependencies using Composer:

```bash
composer install
```

3. Set up environment variables:

```bash
cp .env.example .env
```

Edit the `.env` file with your configuration settings.

## Running the Application

The project includes a convenient script to start the development server:

```bash
composer dev
```

This will start the server at `http://localhost:8080`

## Development

- The main application logic is in `index.php`
- ZIP code validation is handled in `validate_zip.php`
- Styles are in `styles.css`
- JavaScript files are located in the `js/` directory

## Stopping the Server

To stop the development server and kill any running processes on ports 8000 and 8080:

```bash
composer kill-ports
```

## Project Structure

```

```
