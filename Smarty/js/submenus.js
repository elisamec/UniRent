// Pure JavaScript for dropdown hover functionality
    document.addEventListener("DOMContentLoaded", function() {
        var dropdowns = document.querySelectorAll('.nav-tabs .dropdown');

        dropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('mouseenter', function() {
                this.classList.add('show');
                this.querySelector('.dropdown-menu').classList.add('show');
            });

            dropdown.addEventListener('mouseleave', function() {
                this.classList.remove('show');
                this.querySelector('.dropdown-menu').classList.remove('show');
            });
        });
    });