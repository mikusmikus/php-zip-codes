document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("zipCodeForm");
  const zipInput = form.querySelector('input[name="zip_code"]');
  const errorDiv = form.querySelector(".g360-zip-code-form-error");

  const submitButton = form.querySelector('button[type="submit"]');

  const states = {
    initial: document.querySelector(".g360-zip-code-claim-initial"),
    success: document.querySelector(".g360-zip-code-claim-success"),
    error: document.querySelector(".g360-zip-code-claim-error"),
  };

  zipInput.addEventListener("input", function () {
    hideMessages(errorDiv);
    showState(states.initial, states);
  });

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    const zipCode = form.zip_code.value;

    hideMessages(errorDiv);

    if (!zipCode || !/^\d{5}$/.test(zipCode)) {
      showError(errorDiv, "Please enter a valid 5-digit zip code");
      showState(states.initial, states);
      return;
    }

    // Disable form elements during submission
    submitButton.disabled = true;
    zipInput.disabled = true;

    try {
      const response = await validateZipCode(zipCode);

      if (response.status === 403) {
        showError(errorDiv, "Unauthorized origin");
      } else {
        const data = await response.json();

        if (response.ok) {
          handleSuccessResponse(data, states);
        } else {
          handleErrorResponse(data, states);
        }
      }
    } catch (error) {
      handleErrorResponse({ error: "An error occurred while validating the zip code" }, states);
    } finally {
      // Re-enable form elements after submission completes
      submitButton.disabled = false;
      zipInput.disabled = false;
    }
  });
});

function hideMessages(errorDiv) {
  errorDiv.style.display = "none";
}

function showError(errorDiv, message) {
  errorDiv.textContent = message;
  errorDiv.style.display = "block";
}

function showState(stateToShow, states) {
  Object.values(states).forEach((state) => (state.style.display = "none"));
  stateToShow.style.display = "block";
}

function handleSuccessResponse(data, states) {
  if (data.success) {
    showState(states.success, states);
  } else {
    handleErrorResponse(data, states);
  }
}

function handleErrorResponse(data, states) {
  showState(states.error, states);
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
