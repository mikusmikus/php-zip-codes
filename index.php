<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Add required environment variables
$dotenv->required(['API_BASE_URL', 'API_TIMEOUT', 'SERVICE_TOKEN']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zip Code Validator</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="g360-theme g360-theme--dark">
    <div class="g360-container" style="margin-top: 100px">
      <div class="g360-zip-code-section">
        <form class="g360-zip-code-form" id="zipCodeForm">
          <div class="g360-zip-code-form-input-wrapper">
            <input
              type="text"
              name="zip_code"
              class="g360-zip-code-form-input"
              placeholder="Search zip code"
              aria-label="Search zip code"
              required
            />
            <button  
              type="submit"
              class="g360-button g360-button--tertiary g360-zip-code-form-submit-button"
            >
              Submit
            </button>
          </div>
          <div class="g360-zip-code-form-message">
            <div class="g360-zip-code-form-error g360-body-extra-small" aria-live="polite" style="display: none;"></div>
            <div class="g360-zip-code-form-success g360-body-extra-small" aria-live="polite" style="display: none;"></div>
          </div>
        </form>

        <div class="g360-zip-code-claim">
          <div class="g360-zip-code-claim-content">
            <h2 class="g360-h3">Claim your discount</h2>
            <p class="g360-body">
              Enter your zip code to check if you're eligible for a discount. If you don't have a valid zip code,
              please contact us.
            </p>
          </div>
          <div class="g360-zip-code-claim-action">
            <button
              class="g360-button g360-button--primary g360-button--rounded g360-button--small"
            >
              Claim zip code now
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="js/zip-validator.js"></script>
</body>
</html> 