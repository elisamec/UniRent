document.addEventListener("DOMContentLoaded", function() {
    function updateHiddenInput(checkboxId, hiddenInputId) {
        var checkbox = document.getElementById(checkboxId);
        var hiddenInput = document.getElementById(hiddenInputId);
        checkbox.addEventListener('change', function() {
            hiddenInput.value = this.checked ? 'true' : 'false';
        });
    }
    if (file=='addAccommodation' || file=='editAccommodation') {
    updateHiddenInput('men', 'hiddenMen');
    updateHiddenInput('women', 'hiddenWomen');
    updateHiddenInput('animals', 'hiddenAnimals');
    updateHiddenInput('smokers', 'hiddenSmokers');
    } else if (file==='registerStudent' || file=='editProfileStudent') {
        // Assicurati che gli ID corrispondano ai tuoi elementi HTML
        var smokerCheckbox = document.getElementById('smoker');
        var animalsCheckbox = document.getElementById('animals');
        var hiddenSmoker = document.getElementById('hiddenSmoker');
        var hiddenAnimals = document.getElementById('hiddenAnimals');

        if(smokerCheckbox && hiddenSmoker) {
            smokerCheckbox.onclick = function() {
                hiddenSmoker.value = this.checked ? 'true' : 'false';
            };
        }

        if(animalsCheckbox && hiddenAnimals) {
            animalsCheckbox.onclick = function() {
                hiddenAnimals.value = this.checked ? 'true' : 'false';
            };
        }
    } else if (file==='tenants') {
        updateHiddenInput('men', 'hiddenMen');
        updateHiddenInput('women', 'hiddenWomen');
    }
});