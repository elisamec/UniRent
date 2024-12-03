document.addEventListener('DOMContentLoaded', () => {
    const countContainer = document.querySelector('.count');
    const prevBtn = document.querySelector('.prevBtn');
    const nextBtn = document.querySelector('.nextBtn');
    const activeBtn = document.querySelector('.activeBtn');
    const leftContainer = document.querySelector('.leftContainer');
    const rightContainer = document.querySelector('.rightContainer');
    const btns = document.querySelector('.containerBtns');


    const changeActiveBtn = (value) => {
        activeBtn.textContent = value;
        activeBtn.disabled = true;
    }

    const init = () => {
        countContainer.textContent = countPage === 1 ? '1 page' : `${countPage} pages`;
        changeActiveBtn(1);
        updatePaginate(1);
    }

    const handlePrevBtn = () => {
        const curActive = Number(activeBtn.textContent) - 1;
        updatePaginate(curActive);
    }

    const handleNextBtn = () => {
        const curActive = Number(activeBtn.textContent) + 1;
        updatePaginate(curActive);
    }

    const updatePaginate = (value) => {
        changeActiveBtn(value);
        updateBlockBtn(value);
        updateLeftContainer(value);
        updateRightContainer(value);
    }

    const handleBtnNumbers = (e) => {
        const target = e.target.closest('.numberBtn');
        if (target) {
            const pageNumber = Number(target.getAttribute('data-page'));
            updatePaginate(pageNumber);
        }
    }

    const updateBlockBtn = (value) => {
        prevBtn.disabled = value === 1;
        nextBtn.disabled = value === countPage;
    }

    const createButton = (text, classNames = [], disabled = true, pageNumber = null) => {
        const button = document.createElement('button');
        button.textContent = text;
        button.classList.add(...classNames);
        button.disabled = disabled;
        if (pageNumber !== null) {
            button.setAttribute('data-page', pageNumber);
        }
        return button;
    };

    const updateContainer = (container, array, disabledIndexes = []) => {
        container.textContent = '';
        array.forEach((pageNumber, i) => {
            const text = disabledIndexes.includes(i) ? '...' : pageNumber;
            const button = createButton(text, ['w-7', 'h-7', 'mx-2', 'numberBtn', 'w-32'], true, pageNumber);
            container.appendChild(button);
        });
    };

    const updateLeftContainer = (value) => {
        if (value <= 5) {
            updateContainer(leftContainer, Array.from({ length: value - 1 }, (_, i) => i + 1), []);
        } else {
            updateContainer(leftContainer, [1, 2, value - 2, value - 1], [0]);
        }
    }

    const updateRightContainer = (value) => {
        if (value >= countPage - 4) {
            const tail = countPage - value;
            updateContainer(rightContainer, Array.from({ length: tail }, (_, i) => i + value + 1), []);
        } else {
            updateContainer(rightContainer, [value + 1, value + 2, countPage - 1, countPage], [2]);
        }
    }

    prevBtn.addEventListener('click', handlePrevBtn);
    nextBtn.addEventListener('click', handleNextBtn);
    btns.addEventListener('click', handleBtnNumbers);

    init();
});
