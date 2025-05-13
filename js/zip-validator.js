document.addEventListener("DOMContentLoaded", function () {
  // Get zip code form element
  const form = document.getElementById("zipCodeForm");
  if (!form) return;

  // Get claim button
  const claimButton = document.querySelector(".g360-zip-code-claim-button");
  if (claimButton) {
    claimButton.disabled = true;
  }

  // Get input element
  const zipInput = form.querySelector('input[name="zip_code"]');
  const errorDiv = form.querySelector(".g360-zip-code-form-error");
  const successDiv = form.querySelector(".g360-zip-code-form-success");

  // Clear state when user starts typing
  zipInput.addEventListener("input", function () {
    hideMessages(errorDiv, successDiv);
    if (claimButton) claimButton.disabled = true;
  });

  // Handle form submission
  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    // Get form elements
    const zipCode = form.zip_code.value;

    // Reset message displays and disable button
    hideMessages(errorDiv, successDiv);
    if (claimButton) claimButton.disabled = true;

    try {
      // Send validation request
      const response = await validateZipCode(zipCode);
      const data = await response.json();

      // Handle response
      if (response.ok) {
        handleSuccessResponse(data, form, successDiv, errorDiv, claimButton);
      } else {
        showError(errorDiv, data.error);
        if (claimButton) claimButton.disabled = true;
      }
    } catch (error) {
      showError(errorDiv, "An error occurred while validating the zip code");
      if (claimButton) claimButton.disabled = true;
    }
  });
});

// Helper functions
function hideMessages(errorDiv, successDiv) {
  errorDiv.style.display = "none";
  successDiv.style.display = "none";
}

function showError(errorDiv, message) {
  errorDiv.textContent = message;
  errorDiv.style.display = "block";
}

function showSuccess(successDiv, message) {
  successDiv.textContent = message;
  successDiv.style.display = "block";
}

function handleSuccessResponse(data, form, successDiv, errorDiv, claimButton) {
  if (data.success) {
    showSuccess(successDiv, data.message);
    form.reset();
    if (claimButton) claimButton.disabled = false;
  } else {
    showError(errorDiv, data.message);
    if (claimButton) claimButton.disabled = true;
  }
}

async function validateZipCode(zipCode) {
  return fetch("validate_zip.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `zip_code=${encodeURIComponent(zipCode)}`,
  });
}
