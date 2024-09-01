document.addEventListener("DOMContentLoaded", function() {
    const rangeInput = document.querySelectorAll(".range-input input"),
          priceInput = document.querySelectorAll(".price-input input"),
          range = document.querySelector(".slider .progress");
    let priceGap = 1000;
    updateSliderPosition(); // Initial position update

    // Event listeners for range inputs
    rangeInput.forEach((input) => {
      input.addEventListener("input", () => {
        updatePriceInputs();
        updateSliderPosition();
      });
    });

    // Event listeners for price inputs
    priceInput.forEach((input) => {
      input.addEventListener("input", () => {
        updateRangeInputs();
        updateSliderPosition();
      });
    });

    // Function to update price inputs based on range inputs
    function updatePriceInputs() {
      let minVal = parseInt(rangeInput[0].value),
          maxVal = parseInt(rangeInput[1].value);
      priceInput[0].value = minVal;
      priceInput[1].value = maxVal;
    }

    // Function to update range inputs based on price inputs
    function updateRangeInputs() {
      let minPrice = parseInt(priceInput[0].value),
          maxPrice = parseInt(priceInput[1].value);
      rangeInput[0].value = minPrice;
      rangeInput[1].value = maxPrice;
    }

    // Function to update slider position based on range inputs
    function updateSliderPosition() {
      let minVal = parseInt(rangeInput[0].value),
          maxVal = parseInt(rangeInput[1].value);
      range.style.left = (minVal / rangeInput[0].max) * 100 + "%";
      range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
    }
  });