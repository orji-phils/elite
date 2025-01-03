// function to calculate the total grade score
function calculateTotal() {
    var test = parseFloat(document.getElementById("test").value);
var exam = parseFloat(document.getElementById("exam").value);

    total = test + exam;
    document.getElementById("total").value = total;
    return total;
}

function calculateAverage() {
    average = calculateTotal() / 2;

    document.getElementById("average").value = average;
    return average;
}

// add focus and change event for total
document.getElementById("total").addEventListener("focus", calculateTotal);
document.getElementById("total").addEventListener("change", calculateTotal);

// add focus and change event for average
document.getElementById("average").addEventListener("focus", calculateAverage);
document.getElementById("average").addEventListener("change", calculateAverage);
