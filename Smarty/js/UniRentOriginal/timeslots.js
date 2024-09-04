function openEditVisitModal() {
    document.getElementById("visitModal").style.display = "block";
    selectPreselectedValues();
}
switch (preselectedDay) {
    case '0':
        preselectedDay = 'Sunday';
        break;
    case '1':
        preselectedDay = 'Monday';
        break;
     case '2':
        preselectedDay = 'Tuesday';
        break;
     case '3':
        preselectedDay = 'Wednesday';
        break;
     case '4':
        preselectedDay = 'Thursday';
        break;
     case '5':
        preselectedDay = 'Friday';
        break;
     case '6':
        preselectedDay = 'Saturday';
        break;
     default:
        preselectedDay = null;
        break;
}

function selectPreselectedValues() {
    const daySelect = document.getElementById("day");
        daySelect.innerHTML =
          '<option value="" selected disabled>Select a day</option>';
  
        // Populate new options from timeSlots keys
        Object.keys(timeSlots).forEach(function (day) {
          var option = document.createElement("option");
          option.value = day;
          option.textContent = day.charAt(0).toUpperCase() + day.slice(1); // Capitalize the first letter
          daySelect.appendChild(option);
        });
        console.log(preselectedDay);
    if (preselectedDay) {
        const options = daySelect.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === preselectedDay) {
                options[i].selected = true;
                break;
            }
        }
    }
}

document.getElementById("cancelVisit").onclick = function() {
    document.getElementById("visitModal").style.display = "none";
}

document.getElementById("visitClose").onclick = function() {
    document.getElementById("visitModal").style.display = "none";
}

document.getElementById("day").addEventListener("change", function() {
    populateTimeSlots(this.value);
});

function populateTimeSlots(selectedDay) {
    const timeSelect = document.getElementById("time");
    timeSelect.innerHTML = '<option value="" disabled>Select the time</option>';

    if (timeSlots[selectedDay] && typeof timeSlots[selectedDay] === 'object') {
        Object.keys(timeSlots[selectedDay]).forEach(function(key) {
            const option = document.createElement("option");
            option.value = timeSlots[selectedDay][key];
            option.textContent = timeSlots[selectedDay][key];
              if (timeSlots[selectedDay][key] === preselectedTime) {
                 option.selected = true;
              }
            timeSelect.appendChild(option);
        });
    }
}

// Ensure the initial time slots are populated when the page loads if preselected day is provided
if (preselectedDay) {
    populateTimeSlots(preselectedDay);
}