document.addEventListener("DOMContentLoaded", function() {
    const citySelect = document.getElementById("citySelect");
    const uniSelect = document.getElementById("universitySelect");
    const periodSelect = document.getElementById("date");
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1; // Months are zero-indexed in JavaScript
    if (typeof accommodationPeriod === 'undefined') {
        var accommodationPeriod = 10; // Default accommodation period is 9 months
    }
    const startYear = currentMonth >= accommodationPeriod ? currentYear + 1 : currentYear;
    const endYear = startYear + 10;

    // Populate year dropdown
    for (let year = startYear; year <= endYear; year++) {
        const option = new Option(year, year);
        yearSelect.add(option);
    }

    // Select default year if available
    if (typeof defaultYear !== 'undefined') {
        yearSelect.value = defaultYear;
    }

    // Initialize nice-select on page load
    $('select').niceSelect();

    fetch("/UniRent/User/getCities")
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(subjectObject => {
        // Populate city dropdown
        for (let city in subjectObject) {
            let option = new Option(city, city);
            citySelect.add(option);
        }

        // Select default city and populate universities if available
        if (typeof defaultCity !== 'undefined' && subjectObject[defaultCity]) {
            citySelect.value = defaultCity;
            subjectObject[defaultCity].forEach(uniName => {
                let option = new Option(uniName, uniName);
                uniSelect.add(option);
            });
            
            if (typeof defaultUniversity !== 'undefined' && subjectObject[defaultCity].includes(defaultUniversity)) {
                uniSelect.value = defaultUniversity;
            }

            if (typeof defaultPeriod !== 'undefined') {
                periodSelect.value = defaultPeriod;
            }
        }

        // Update nice-select after adding options
        $('select').niceSelect('update');

        // Add event listener for city dropdown change
        citySelect.onchange = function() {
            const selectedCity = citySelect.value;

            // Clear the university dropdown except the first placeholder option
            uniSelect.length = 1;

            if (selectedCity && subjectObject[selectedCity]) {
                // Populate university dropdown
                subjectObject[selectedCity].forEach(uniName => {
                    let option = new Option(uniName, uniName);
                    uniSelect.add(option);
                });

                if (typeof defaultUniversity !== 'undefined' && subjectObject[selectedCity].includes(defaultUniversity)) {
                    uniSelect.value = defaultUniversity;
                }

                if (typeof defaultPeriod !== 'undefined') {
                    periodSelect.value = defaultPeriod;
                }

                // Update nice-select after adding options
                $('select').niceSelect('update');
            }
        };
    })
    .catch(error => console.error('Error:', error));
});
