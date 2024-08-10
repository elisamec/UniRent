document.addEventListener('DOMContentLoaded', () => {
    const openSliderLink = document.getElementById('openSlider');
    const sliderContainer = document.getElementById('sliderContainer');
    const closeSliderBtn = document.getElementById('closeSlider');
    const sliderContent = document.querySelector('.slider-content');
    const mainImage = document.getElementById('mainImage');
    const overlay = document.querySelector('.overlay');
    let currentIndex = 0;

    // Display the first image in the first container if available
    if (images.length > 0) {
        mainImage.src = images[0];
    } else {
        // Hide the overlay if no images are available
        overlay.style.display = 'none';
        // Set the default image
        mainImage.src = '/UniRent/Smarty/images/noPic.png';
    }

    openSliderLink.addEventListener('click', (event) => {
        event.preventDefault();  // Prevent the default anchor behavior
        if (images.length > 0) {
            sliderContainer.classList.remove('hidden');
            showImage(currentIndex);
        }
    });

    closeSliderBtn.addEventListener('click', () => {
        sliderContainer.classList.add('hidden');
    });

    document.querySelector('.prev').addEventListener('click', () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
        showImage(currentIndex);
    });

    document.querySelector('.next').addEventListener('click', () => {
        currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
        showImage(currentIndex);
    });

    function showImage(index) {
        sliderContent.innerHTML = `<img src="${images[index]}" alt="Image ${index + 1}">`;
    }
});