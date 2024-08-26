document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();
    const currentMonth = new Date().getMonth() + 1; // I mesi in JavaScript sono indicizzati da 0
    const startYear = currentMonth >= 10 ? currentYear + 1 : currentYear;
    const endYear = startYear + 10;

    for (let year = startYear; year <= endYear; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }
});