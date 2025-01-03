fields = {
    "email": [/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, "Invalid email format."],
    "firstName": [/^[a-zA-Z]+$/, "Invalid First Name. Only alphabets allowed."],
    "lastName": [/^[a-zA-Z]+$/, "Invalid Last Name. Only alphabets allowed."],
    "class": [/^[a-zA-Z]+$/, "Invalid class entery. Only alphabets allowed."]
};

document.addEventListener("DOMContentLoaded", () => {
    for (const field in fields) {
        document.getElementById(field).addEventListener("change", function () {
            validateField(field, fields[field][0], fields[field][1]);
        });
    }

    document.getElementById("date").addEventListener("change", get_age);
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

// function to get user's age
function get_age() {
    let dateOfBirth = document.getElementById("date").value;
    let birthDate = new Date(dateOfBirth);
    let currentDate = new Date();

    // Calculate the age in years
    let age = currentDate.getFullYear() - birthDate.getFullYear();
    let monthDifference = currentDate.getMonth() - birthDate.getMonth();
    let dayDifference = currentDate.getDate() - birthDate.getDate();

    // Adjust age if the birthday hasn't occurred yet this year
    if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
        age--;
    }

    // Update the age field
    document.getElementById("age").value= age;

    // alert("User's Age = " + age); // Optional alert for debugging
}
