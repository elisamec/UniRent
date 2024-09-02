
    // Elements
    const dayInput = document.getElementById("Date");
    const monthInputs = document.querySelectorAll("input[name='month']");
    const hiddenDateInput = document.getElementById("date");

    // Function to update max days based on selected month
    function updateMaxDays() {
        const selectedMonth = document.querySelector("input[name='month']:checked").value;
        if (selectedMonth === "september") {
            dayInput.max = 30;
        } else if (selectedMonth === "october") {
            dayInput.max = 31;
        }
        // Ensure the current day value is within the new max limit
        if (dayInput.value > dayInput.max) {
            dayInput.value = dayInput.max;
        }
    }

    // Function to calculate the nearest future date
    function calculateNearestFutureDate() {
       if (dayInput.value === "") {
            hiddenDateInput.value = "";
        } else {
        
        const day = parseInt(dayInput.value, 10);
        const month = document.querySelector("input[name='month']:checked").value;

        let selectedDate = new Date();
        selectedDate.setHours(0, 0, 0, 0);

        if (month === "september") {
            selectedDate.setMonth(8); // September is month 8 (0-indexed)
        } else if (month === "october") {
            selectedDate.setMonth(9); // October is month 9 (0-indexed)
        }

        selectedDate.setDate(day);
        let currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0);

        if (selectedDate < currentDate) {
            selectedDate.setFullYear(currentDate.getFullYear() + 1);
        } else {
            selectedDate.setFullYear(currentDate.getFullYear());
        }

        hiddenDateInput.value = selectedDate.toISOString().split('T')[0];
    }
    }

    // Event listeners
    dayInput.addEventListener("input", function() {
        calculateNearestFutureDate();
    });

    monthInputs.forEach(input => input.addEventListener("change", function() {
        updateMaxDays();
        calculateNearestFutureDate();
    }));
    if (dayInput && monthInputs) {
    // Initial setup
    updateMaxDays();
    calculateNearestFutureDate();
    }

if (birthDateString) {
    var parts = birthDateString.split('/');
    if (parts.length === 3) {
          var formattedDate = parts[2] + '-' + parts[0].padStart(2, '0') + '-' + parts[1].padStart(2, '0');
          document.getElementById('birthDate').value = formattedDate;
    }
 }