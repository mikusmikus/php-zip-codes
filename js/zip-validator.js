document.addEventListener("DOMContentLoaded", function () {
  // Get zip code form element
  const form = document.getElementById("zipCodeForm");
  if (!form) return;

  // Handle form submission
  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    // Get form elements
    const zipCode = form.zip_code.value;
    const errorDiv = form.querySelector(".g360-zip-code-form-error");
    const successDiv = form.querySelector(".g360-zip-code-form-success");

    // Reset message displays
    hideMessages(errorDiv, successDiv);

    try {
      // Send validation request
      const response = await validateZipCode(zipCode);
      const data = await response.json();

      // Handle response
      if (response.ok) {
        handleSuccessResponse(data, form, successDiv, errorDiv);
      } else {
        showError(errorDiv, data.error);
      }
    } catch (error) {
      showError(errorDiv, "An error occurred while validating the zip code");
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

function handleSuccessResponse(data, form, successDiv, errorDiv) {
  if (data.success) {
    showSuccess(successDiv, data.message);
    form.reset();
  } else {
    showError(errorDiv, data.message);
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
