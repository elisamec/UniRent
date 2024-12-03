// Function to open the payment form with pre-filled values
function openEditForm(cardTitle, cardNumber, expiryDate, CVV, Name, Surname) {
    
    document.getElementById('cardTitle1').value = cardTitle;
    document.getElementById('cardnumber1').value = cardNumber;
    document.getElementById('expirydate1').value = expiryDate;
    document.getElementById('cvv1').value = CVV;
    document.getElementById('name1').value = Name;
    document.getElementById('surname1').value = Surname;

    // Display the modal
    document.getElementById('paymentUpdateModal').style.display = 'block';
}

// Function to close the modal
function closeEditModal() {
    // Hide the modal
    document.getElementById('paymentUpdateModal').style.display = 'none';
}
    // Function to handle updating the payment method
function updatePaymentMethod() {
    // Get values from the form
    let cardTitle = document.getElementById('cardTitle1').value;
    let cardNumber = document.getElementById('cardnumber1').value.replace(/\s+/g, ''); // Remove spaces
    let expiryDate = document.getElementById('expirydate1').value;
    let cvv = document.getElementById('cvv1').value;
    let name = document.getElementById('name1').value;
    let surname = document.getElementById('surname1').value;
    
    // Find the corresponding card in the array by cardNumber
    let index = cards.findIndex(card => card.number === cardNumber);
    
    if (index !== -1) {
        // Update the card in the array
        cards[index] = {
            number: cardNumber,
            cardTitle: cardTitle,
            expiryDate: expiryDate,
            cvv: cvv,
            name: name,
            surname: surname
        };
        
        // For demonstration, log the updated card
        
        // Optionally, you can submit the form or perform other actions here
        // document.getElementById('paymentUpdateForm').submit();
        
        // Close the modal or perform any other UI update
        closeEditModal();
    } else {
        console.error('Card not found in array.');
    }
}
let cards = [];
    if (Array.isArray(cardsData)) {
        cards = cardsData;
    } else if (cardsData && typeof cardsData === 'object') {
        cards = [cardsData]; // Wrap the single object in an array
    } else {
        console.error('Invalid cardsData format:', cardsData);
    }

    function displayCards(cards) {
    const container = document.getElementById('cardsContainer');

    if (container) {
        // Sort cards to ensure the main card is first
        cards.sort((a, b) => b.isMain - a.isMain);

        if (cards.length === 0) {
            container.innerHTML = '<div class="container"><h1 class="noCards">You don\'t have any credit cards memorized</h1></div>';
        } else {
            container.innerHTML = ''; // Clear container before adding new cards
            let foundNewMain = false;

            cards.forEach(card => {
                const cardElement = document.createElement('div');
                cardElement.className = 'review';

                // Check if the card is expired
                let isExpired = false;
                const currentDate = new Date();
                const [expMonth, expYear] = card.expiryDate.split('/').map(Number);
                const expDate = new Date(`20${expYear}`, expMonth - 1, 1);

                if (expDate < currentDate) {
                    isExpired = true;
                }

                let cardNumber = card.number.replace(/\s/g, '');
                let groups = cardNumber.match(/.{1,4}/g);
                let result = "";

                for (let i = 0; i < groups.length; i++) {
                    result += `<p>${groups[i]}</p>\n`;
                }

                let buttonHTML = '';
                if (card.isMain) {
                    if (isExpired) {
                        card.isMain = false; // Remove main status from expired card
                    } else {
                        buttonHTML = `<h2 class="paymentMain"> Main </h2>`;
                        foundNewMain = true;
                    }
                }

                if (!card.isMain && !isExpired && !foundNewMain) {
                    // Make the first non-expired card the main card if no main card is set
                    card.isMain = true;
                    buttonHTML = `<h2 class="paymentMain"> Main </h2>`;
                    foundNewMain = true;
                } else if (!card.isMain) {
                    if (isExpired) {
                        buttonHTML = `<button class="button-spec" disabled style="opacity: 0.5; cursor: not-allowed;"> Make Main </button>`;
                    } else {
                        buttonHTML = `<button class="button-spec" onclick="fetchCard('${card.number}')"> Make Main </button>`;
                    }
                }

                cardElement.innerHTML = `
                    <div class="paymentGrid">
                        <div class="divPAY1">
                            <div class="container1 ${isExpired ? 'expired-card' : ''}">
                                <div class="card1">
                                    <div class="card-inner">
                                        <div class="front">
                                            <img src="https://i.ibb.co/PYss3yv/map.png" class="map-img">
                                            <div class="row1">
                                                <img src="https://i.ibb.co/G9pDnYJ/chip.png" width="60px">
                                                <h1 class="paymentTitle"> ${card.title} ${isExpired ? '(expired)' : ''} </h1>
                                            </div>
                                            <div class="row1 card-no">
                                                ${result}
                                            </div>
                                            <div class="row1 card-holder">
                                                <p>CARD HOLDER</p>
                                                <p>VALID TILL</p>
                                            </div>
                                            <div class="row1 name">
                                                <p>${card.name} ${card.surname}</p>
                                                <p>${card.expiryDate}</p>
                                            </div>
                                        </div>
                                        <div class="back">
                                            <img src="https://i.ibb.co/PYss3yv/map.png" class="map-img">
                                            <div class="bar"></div>
                                            <div class="row1 card-cvv">
                                                <div>
                                                    <img src="https://i.ibb.co/S6JG8px/pattern.png">
                                                </div>
                                                <p>${card.cvv}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="divPAY2">
                            ${buttonHTML}
                            <button class="button-spec" onclick="openEditForm('${card.title}', '${card.number}', '${card.expiryDate}', '${card.cvv}', '${card.name}', '${card.surname}')">Edit</button>
                        </div>
                    </div>
                    <button class="button-spec little button-delete" onclick="openConfirmModal('${card.number}')">-</button>
                `;

                container.appendChild(cardElement);
            });

            // If no main card was found, set the first non-expired card as main
            if (!foundNewMain) {
                const firstNonExpiredCard = cards.find(card => !card.isExpired);
                if (firstNonExpiredCard) {
                    firstNonExpiredCard.isMain = true;
                    displayCards(cards); // Re-display cards with the updated main card
                }
            }
        }
    } else {
        console.error("Container not found!"); // Debugging: Error if container is not found
    }
}



    function openModal() {
        document.getElementById('paymentModal').style.display = "block";
    }

    // Function to close the add payment modal
    function closeModal() {
        document.getElementById('paymentModal').style.display = "none";
    }

    // Function to open the confirm deletion modal
    function openConfirmModal(cardNumber) {
        const confirmYesButton = document.getElementById('confirmDeleteCreditCard');
        confirmYesButton.setAttribute('data-card-number', cardNumber);
        document.getElementById('deleteConfirmModal').style.display = "block";
    }

    // Function to close the confirm deletion modal
    function closeConfirmModal() {
        document.getElementById('deleteConfirmModal').style.display = "none";
    }

    // Function to add a payment method
    function addPaymentMethod() {
        const cardTitle = document.getElementById('cardTitle').value;
        const cardNumber = document.getElementById('cardnumber').value;
        const expiryDate = document.getElementById('expirydate').value;
        const cvv = document.getElementById('cvv').value;
        const name = document.getElementById('name').value;
        const surname = document.getElementById('surname').value;

        // Logic to handle the new card information (e.g., make an API call to save the card)
        if (cards.length === 0) {
            // If there are no cards, make the new card the main card
            cards.push({
                number: cardNumber,
                title: cardTitle,
                expiryDate: expiryDate,
                cvv: cvv,
                name: name,
                surname: surname,
                isMain: true
            });
        } else {
            cards.push({
                number: cardNumber,
                title: cardTitle,
                expiryDate: expiryDate,
                cvv: cvv,
                name: name,
                surname: surname,
                isMain: false
            });
        }

        // Update the cards array and re-display
        cards.push(newCard);
        displayCards(cards);

        // Close the modal after submitting the form
        closeModal();
    }

    // Function to delete a card
    function deleteCard(cardNumber) {
        
        // Send a request to the server to delete the card
        fetch(`/UniRent/Contract/deleteCreditCard/${cardNumber}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (response.ok) {
                // Remove the card from the array and update the UI
                const updatedCards = cards.filter(card => card.number !== cardNumber);
                displayCards(updatedCards);
                closeConfirmModal();
            } else {
                // Handle error
                console.error('Failed to delete card');
            }
        });
    }


    // Function to make a card the main card   (per il momento non riesco a usarla Eli, mi servirebbe metterci una fetch per attendere la risposta del server, ci ho provato ma non sono riuscito :-(  ))
    // sarebbe bello fare come qui sopra in deleteCard
    function makeMain(cardNumber) {   

            
                let mainCard = null;
                const otherCards = [];
        
                cards.forEach(card => {
                if (card.number === cardNumber) 
                {
                    card.isMain = true;
                    mainCard = card;
                } 
                else 
                {
                    card.isMain = false;
                    otherCards.push(card);
                }
                });
                if (mainCard) {
                cards = [mainCard, ...otherCards];
                } else {
                     console.error('Card not found:', cardNumber);
                }
        
                displayCards(cards);      
    }

    function fetchCard(cardNumber)
    {
        // mando la richiesta al server
  
        fetch(`/UniRent/Contract/makeMainCreditCard/${cardNumber}`, {
            method: "GET"
        })
        .then(response=>{
            if(response.ok)
            {
                makeMain(cardNumber);
            }
        })
    }

    // Event listener for the confirmation button
    document.getElementById('confirmDeleteCreditCard').addEventListener('click', function() {
        const cardNumber = this.getAttribute('data-card-number');
        deleteCard(cardNumber);
    });

    // Close the modal when the user clicks outside of the modal
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal();
            closeConfirmModal();
        }
    }

    // Call the function to display cards if cards is an array
    if (Array.isArray(cards)) {
        displayCards(cards);
    } else {
        console.error('cards is not an array:', cards);
    }