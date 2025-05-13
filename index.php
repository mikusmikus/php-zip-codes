<?php
require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Add required environment variables
$dotenv->required(['API_BASE_URL', 'API_TIMEOUT', 'SERVICE_TOKEN']);

$message = '';
$error = '';
$formErrors = [];

// Pre-fill form values from POST data if there were errors
$zipCodeValue = $_POST['zip_code'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Store zip code value
    $zipCode = $_GET['zip_code'] ?? '';
    error_log("Submitted zip code: " . $zipCode);
    // Validate zip code format (5 digits)
    if (empty($_GET['zip_code']) || !preg_match('/^\d{5}$/', $_GET['zip_code'])) {
        $formErrors['zip_code'] = 'Please enter a valid 5-digit zip code';
    }

    $apiUrl = $_ENV['API_BASE_URL'] . '/gapi/services/wordpress/zip-codes/validate/';

    if (empty($formErrors)) {
        try {
            $client = new Client([
                'base_uri' => $_ENV['API_BASE_URL'],
                'timeout' => $_ENV['API_TIMEOUT']
            ]);

            error_log("Full API URL: " . $apiUrl);

            // Submit zip code to API with ServiceToken from env
            $response = $client->get('/gapi/services/wordpress/zip-codes/validate/', [
                'query' => ['zipcode' => $_GET['zip_code']],
                'headers' => [
                    'Authorization' => 'ServiceToken ' . $_ENV['SERVICE_TOKEN']
                ]
            ]);

            error_log("Response: " . $response->getBody());

            if ($response->getStatusCode() === 200) {
                $message = 'Zip code validated successfully!';
                $zipCodeValue = '';
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            $error = 'Error: ' . $e->getMessage();
        }
    }
}
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
        <form class="g360-zip-code-form" method="GET">
          <div class="g360-zip-code-form-input-wrapper">
            <input
              type="text"
              name="zip_code"
              class="g360-zip-code-form-input"
              placeholder="Search zip code"
              aria-label="Search zip code"
              value="<?php echo htmlspecialchars($zipCodeValue); ?>"
           
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
            <?php if (!empty($formErrors['zip_code'])): ?>
            <div class="g360-zip-code-form-error g360-body-extra-small" aria-live="polite">
              <?php echo htmlspecialchars($formErrors['zip_code']); ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($message)): ?>
            <div class="g360-zip-code-form-success g360-body-extra-small" aria-live="polite">
              <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>
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
</body>
</html> 