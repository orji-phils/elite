fields = {
        "password": [/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, "Password must be at least 8 characters, include uppercase, lowercase, digit, and a special character."],
        "confirmPassword": [/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/, "Password must be at least 8 characters, include uppercase, lowercase, digit, and a special character."]
    };

document.addEventListener("DOMContentLoaded", () => {
    for (const field in fields) {
        document.getElementById(field).addEventListener("change", function () {
            validateField(field, fields[field][0], fields[field][1]);
        });
    }

    document.getElementById("confirmPassword").addEventListener("keyup", comparePasswords);

    document.querySelector(".toggle-password").addEventListener("click", function () {
        togglePasswordVisibility("password", ".toggle-password");
    });

    document.querySelector(".toggle-password").addEventListener("click", function () {
        togglePasswordVisibility("confirmPassword", ".toggle-password");
    });
});

function validateField(fieldId, regex, errorMessage) {
    const value = document.getElementById(fieldId).value.trim();
    const errorContainer = document.getElementById(fieldId + "Error");

    if (!value) {
        errorContainer.innerHTML = `${fieldId} is required.`;
    } else if (!regex.test(value)) {
        errorContainer.innerHTML = errorMessage;
    } else {
        errorContainer.innerHTML = "";
    }
}

function comparePasswords() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const errorContainer = document.getElementById("confirmPasswordError");

    if (password !== confirmPassword) {
        errorContainer.innerHTML = "Passwords do not match.";
    } else {
        errorContainer.innerHTML = "";
    }
}

function togglePasswordVisibility(passwordId, toggleClass) {
    const passwordInput = document.getElementById(passwordId);
    const toggle = document.querySelector(toggleClass);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggle.textContent = "Hide Password";
    } else {
        passwordInput.type = "password";
        toggle.textContent = "Show Password";
    }
}
