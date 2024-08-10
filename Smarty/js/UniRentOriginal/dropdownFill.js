document.addEventListener("DOMContentLoaded", function() {
    const citySelect = document.getElementById("citySelect");
    const uniSelect = document.getElementById("universitySelect");
    const periodSelect = document.getElementById("date");

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

        // Update nice-select after adding options
        $('select').niceSelect('update');

        if (defaultCity && subjectObject[defaultCity]) {
            citySelect.value = defaultCity;
            subjectObject[defaultCity].forEach(uniName => {
                let option = new Option(uniName, uniName);
                uniSelect.add(option);
            });
            $('select').niceSelect('update');
            if (defaultUniversity && subjectObject[defaultCity].includes(defaultUniversity)) {
                uniSelect.value = defaultUniversity;
            }
        }

        // Add event listener for city dropdown change
        citySelect.onchange = function() {
            const selectedCity = citySelect.value;

            // Clear the university dropdown
            uniSelect.length = 1;

            if (selectedCity && subjectObject[selectedCity]) {
                // Populate university dropdown
                subjectObject[selectedCity].forEach(uniName => {
                    let option = new Option(uniName, uniName);
                    uniSelect.add(option);
                });

                // Update nice-select after adding options
                $('select').niceSelect('update');
                if (defaultUniversity && subjectObject[selectedCity].includes(defaultUniversity)) {
                    uniSelect.value = defaultUniversity;
                }
                if (defaultPeriod) {

                    periodSelect.value = defaultPeriod;

                }$('select').niceSelect('update');
            }
        }
    })
    .catch(error => console.error('Error:', error));
});