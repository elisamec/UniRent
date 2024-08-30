// Get the visit availability modal
var avModal = document.getElementById("popup");
var initialVisitData = [];

// Open modal and store initial data
function openModal() {
    avModal.style.display = "block";
    initialVisitData = JSON.parse(JSON.stringify(getVisitData()));
}

// Close modal without saving
function cancelVisitModal() {
    setVisitData(initialVisitData);
    closeModal();
}

function closeModal() {
    avModal.style.display = "none";
}

// Add more availability input fields
function addAvailability() {
    let container = document.getElementById('availabilityContainer');
    let availability = document.createElement('div');
    availability.className = 'availability';
    availability.innerHTML = `
        <button type="button" onclick="removeAvailability(this)" class="button-spec little">-</button>
        <label for="duration">Visit Duration (minutes):</label>
        <input type="number" class="duration" name="duration" title="Please enter a number" min="10" value="10">
        <label for="day">Weekday:</label>
            <select id="day" name="day">
                <option value="" disabled selected>Select</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        <label for="start">Availability start:</label>
        <input type="time" class="start" name="start">
        <label for="end">Availability end:</label>
        <input type="time" class="end" name="end">
    `;
    container.appendChild(availability);

    // Add event listeners to start and end time inputs
    const startInput = availability.querySelector('.start');
    const endInput = availability.querySelector('.end');
    // Ensure new duration input inherits the current duration value
    const durationInput = availability.querySelector('.duration');
    const currentDurationValue = getCurrentDurationValue();
    if (currentDurationValue !== null) {
        durationInput.value = currentDurationValue;
    }

    // Update duration inputs synchronization
    updateDurationSynchronization();

    function adjustEndTime() {
        let duration = durationInput.value ? parseInt(durationInput.value) : 10;
        let newEndTime = new Date('1970-01-01T' + startInput.value + ':00');
        newEndTime.setHours(newEndTime.getHours()+1);
        newEndTime.setMinutes(newEndTime.getMinutes() + duration);
        endInput.value = newEndTime.toISOString().slice(11, 16);
    }

    startInput.addEventListener('change', function() {
        if (endInput.value && startInput.value > endInput.value) {
            adjustEndTime();
        }
    });

    endInput.addEventListener('change', function() {
        if (startInput.value && endInput.value < startInput.value) {
            adjustEndTime();
        }
    });

    durationInput.addEventListener('change', adjustEndTime);
}

// Function to get the current duration value
function getCurrentDurationValue() {
    let durationInputs = document.querySelectorAll('.duration');
    if (durationInputs.length > 0) {
        return durationInputs[0].value || null;
    }
    return null;
}

// Function to update synchronization of duration inputs
function updateDurationSynchronization() {
    // Get all duration inputs
    let durationInputs = document.querySelectorAll('.duration');

    // Add event listener to each duration input
    durationInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            // Check if the input value is empty
            if (input.value.trim() === '') {
                input.value = 10; // Default to 10 if empty
            }
            // Update all other duration inputs
            durationInputs.forEach(function(otherInput) {
                if (otherInput !== input) {
                    otherInput.value = input.value;
                }
            });
        });
    });
}

// Initial call to ensure synchronization for existing elements
updateDurationSynchronization();




// Remove an availability input field
function removeAvailability(button) {
    let availability = button.parentNode;
    availability.parentNode.removeChild(availability);
}

// Get current visit data from the form
function getVisitData() {
    let availabilities = document.getElementsByClassName('availability');
    let data = [];
    for (let i = 0; i < availabilities.length; i++) {
        let durationInput = availabilities[i].querySelector('input[name="duration"]');
        let dayInput = availabilities[i].querySelector('select[name="day"]');
        let startInput = availabilities[i].querySelector('input[name="start"]');
        let endInput = availabilities[i].querySelector('input[name="end"]');

        // Check if inputs are found before accessing their values
        if (durationInput && dayInput && startInput && endInput) {
            let duration = durationInput.value;
            let day = dayInput.value;
            let start = startInput.value;
            let end = endInput.value;
            data.push({ duration, day, start, end });
        } else {
            console.error("One or more input fields not found in availability element:", availabilities[i]);
        }
    }
    return data;
}

// Set visit data in the form
function setVisitData(data) {
    let container = document.getElementById('availabilityContainer');
    container.innerHTML = '';
    for (let i = 0; i < data.length; i++) {
        let availability = document.createElement('div');
        availability.className = 'availability';
        availability.innerHTML = `
            <button type="button" onclick="removeAvailability(this)" class="button-spec little">-</button>
            <label for="duration">Visit Duration (minutes):</label>
            <input type="number" id="duration" name="duration" title="Please enter a number" value="${data[i].duration}" min="10">
            <label for="day">Weekday:</label>
            <select id="day" name="day">
                <option value="" disabled>Select a weekday</option>
                <option value="Monday" ${data[i].day === 'Monday' ? 'selected' : ''}>Monday</option>
                <option value="Tuesday" ${data[i].day === 'Tuesday' ? 'selected' : ''}>Tuesday</option>
                <option value="Wednesday" ${data[i].day === 'Wednesday' ? 'selected' : ''}>Wednesday</option>
                <option value="Thursday" ${data[i].day === 'Thursday' ? 'selected' : ''}>Thursday</option>
                <option value="Friday" ${data[i].day === 'Friday' ? 'selected' : ''}>Friday</option>
                <option value="Saturday" ${data[i].day === 'Saturday' ? 'selected' : ''}>Saturday</option>
                <option value="Sunday" ${data[i].day === 'Sunday' ? 'selected' : ''}>Sunday</option>
            </select>
            <label for="start">Availability start:</label>
            <input type="time" id="start" name="start" value="${data[i].start}">
            <label for="end">Availability end:</label>
            <input type="time" id="end" name="end" value="${data[i].end}">
        `;
        container.appendChild(availability);
    }
}
function setEditVisitData(data) {
    let container = document.getElementById('availabilityContainer');
    container.innerHTML = '';
    // Assuming 'data' is your object with availability details
    for (let day in data) {
        if (data.hasOwnProperty(day)) {
            let availability = document.createElement('div');
            availability.className = 'availability';
            availability.innerHTML = `
                <button type="button" onclick="removeAvailability(this)" class="button-spec little">-</button>
                <label for="duration">Visit Duration (minutes):</label>
                <input type="number" class="duration" name="duration" value="${data[day].duration}" min="10">
                <label for="day">Weekday:</label>
                <select name="day" class="dayV" required>
                    <option value="" disabled selected>Select</option>
                    <option value="Monday" ${day === 'Monday' ? 'selected' : ''}>Monday</option>
                    <option value="Tuesday" ${day === 'Tuesday' ? 'selected' : ''}>Tuesday</option>
                    <option value="Wednesday" ${day === 'Wednesday' ? 'selected' : ''}>Wednesday</option>
                    <option value="Thursday" ${day === 'Thursday' ? 'selected' : ''}>Thursday</option>
                    <option value="Friday" ${day === 'Friday' ? 'selected' : ''}>Friday</option>
                    <option value="Saturday" ${day === 'Saturday' ? 'selected' : ''}>Saturday</option>
                    <option value="Sunday" ${day === 'Sunday' ? 'selected' : ''}>Sunday</option>
                </select>
                <label for="start">Availability start:</label>
                <input type="time" class="start" name="start" value="${data[day].start}">
                <label for="end">Availability end:</label>
                <input type="time" class="end" name="end" value="${data[day].end}">
            `;
            container.appendChild(availability);
        
        // Add event listeners to start and end time inputs
const startInput = availability.querySelector('.start');
const endInput = availability.querySelector('.end');
// Ensure new duration input inherits the current duration value
const durationInput = availability.querySelector('.duration');
const currentDurationValue = getCurrentDurationValue();
if (currentDurationValue !== null) {
    durationInput.value = currentDurationValue;
}

// Update duration inputs synchronization
updateDurationSynchronization();

function adjustEndTime() {
    let duration = durationInput.value ? parseInt(durationInput.value) : 10;
    let newEndTime = new Date('1970-01-01T' + startInput.value + ':00');
    newEndTime.setHours(newEndTime.getHours()+1);
    newEndTime.setMinutes(newEndTime.getMinutes() + duration);
    endInput.value = newEndTime.toISOString().slice(11, 16);
}

startInput.addEventListener('change', function() {
    if (endInput.value && startInput.value > endInput.value) {
        adjustEndTime();
    }
});

endInput.addEventListener('change', function() {
    if (startInput.value && endInput.value < startInput.value) {
        adjustEndTime();
    }
});

durationInput.addEventListener('change', adjustEndTime);
}

// Initial call to ensure synchronization for existing elements
updateDurationSynchronization();
        }
}

// Save visit availability data when the pop-up form is submitted
let visitForm = document.getElementById('visitAvailabilityForm');
visitForm.addEventListener('submit', function(event) {
    event.preventDefault();

    let data = getVisitData();
    sessionStorage.setItem('availabilities', JSON.stringify(data));

    // Store the JSON data in the hidden input field in the main form
    let visitAvailabilityData = document.getElementById('visitAvailabilityData');
    visitAvailabilityData.value = JSON.stringify(data);

    closeModal();
});
if (accommodationData) {
    document.getElementById("title").value = accommodationData.title;
    document.getElementById("price").value = accommodationData.price;
    document.getElementById("deposit").value = accommodationData.deposit;
    document.getElementById("Date").value = accommodationData.startDate;
    let month = accommodationData.month == 'september' ? document.getElementById("september") : document.getElementById("october");
    month.checked = true;
    document.getElementById("date").value = accommodationData.date;
    document.getElementById("address").value = accommodationData.address;
    document.getElementById("city").value = accommodationData.city;
    document.getElementById("postalCode").value = accommodationData.postalCode;
    document.getElementById("description").value = accommodationData.description;
    let men = accommodationData.men === true ? true : false;
    let women = accommodation.women === true ? true : false;
    let animals = accommodationData.animals === true ? true : false;
    let smokers = accommodationData.smokers === true ? true : false;
    document.getElementById("men").checked = men;
    document.getElementById("hiddenMen").value = men.toString();
    document.getElementById("women").checked = women;
    document.getElementById("hiddenWomen").value = women.toString();
    document.getElementById("animals").checked = animals;
    document.getElementById("hiddenAnimals").value = animals.toString();
    document.getElementById("smokers").checked = smokers;
    document.getElementById("hiddenSmokers").value = smokers.toString();
    document.getElementById("places").value = accommodationData.places;
    if (visitData) {
        setEditVisitData(visitData);
    }
}