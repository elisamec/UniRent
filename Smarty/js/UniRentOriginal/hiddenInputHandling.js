document.addEventListener("DOMContentLoaded", function() {
    function updateHiddenInput(checkboxId, hiddenInputId) {
        var checkbox = document.getElementById(checkboxId);
        var hiddenInput = document.getElementById(hiddenInputId);
        checkbox.addEventListener('change', function() {
            hiddenInput.value = this.checked ? 'true' : 'false';
        });
    }

    updateHiddenInput('men', 'hiddenMen');
    updateHiddenInput('women', 'hiddenWomen');
    updateHiddenInput('animals', 'hiddenAnimals');
    updateHiddenInput('smokers', 'hiddenSmokers');
});