<!DOCTYPE html>
<html>
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, eimum-scale=1">
      <!-- site metas -->
      <title>UniRent</title>
      <link rel="icon" href="/UniRent/Smarty/images/favicon.png" type="image/png">
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="/UniRent/Smarty/css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="/UniRent/Smarty/images/fevicon.png" type="image/gif" />
      <!-- font css -->
      <link href="https://fonts.googleapis.com/css2?family=Sen:wght@400;700;800&display=swap" rel="stylesheet">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="/UniRent/Smarty/css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/fontawesome.min.css">
      <!-- Include Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
      <link rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/home.css">
      <link\ rel="stylesheet" type="text/css" href="/UniRent/Smarty/css/cookie.css">
   </head>
   <body onload="on()">
      <div class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <a class="navbar-brand"href="/UniRent/Student/home"><img src="/UniRent/Smarty/images/logo.png"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item">
                        <a class="nav-link" href="/UniRent/Student/home">Home</a>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reservations</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="#">Accepted</a>
                           <a class="dropdown-item" href="#">Waiting</a>
                        </div>
                     </li>
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contracts</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="#">Ongoing</a>
                           <a class="dropdown-item" href="#">Past</a>
                           <a class="dropdown-item" href="#">Future</a>
                        </div>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="#">Posted Reviews</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href = "#">Visits</a>
                     </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                     <div class="login_bt">
                        <ul>
                           <li><a href="/UniRent/Student/profile" class="active"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>Profile</a></li>
                        </ul>
                     </div>
                  </form>
               </div>
            </nav>
         </div>
      </div>
      <div class="path">
            <p><a href="/UniRent/Student/home">Home</a> / <a href="/UniRent/Student/profile">Profile</a> / Payment Methods </p>
      </div>
      <div class="profile">
         <div class="row">
         <div class="col-md-3">
         <div class="sidebar">
         <div class="col-md-3">
            <div class="sidebar_but"><a href="/UniRent/Student/profile">Profile</a></div>
            </div>
            <div class="col-md-3">
            <div class="sidebar_but"><a href="/UniRent/Student/reviews">Reviews</a></div>
            </div>
            <div class="col-md-3">
            <div class="sidebar_but active"><a href="/UniRent/Student/paymentMethods">Payment Methods</a></div>
            </div>
            <div class="col-md-3">
            <div class="sidebar_but log"><a href="/UniRent/User/logout">Logout</a></div>
            </div>
         </div>
         </div>

         <div class="col-md-9">
            <div class="Properties_taital_main layout">
               <h1 class="Properties_taital">Your Payment Methods</h1>
               <hr class="border_main">
            </div>
            <div id="cardsContainer"></div>
                        <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="button_main">
                     <button class="button-spec final pay" onclick="openModal()">Add Payment Method</button>
                  </div>
               </div>
            </div>
         </div>
            </div>
            
            </div>
            
         
         </div>
      </div>
      </div>
<div id="paymentModal" class="resModal">
    <div class="resModal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 class="resModal-head">Add Payment Method</h2>
        <form id="paymentForm" action="/UniRent/Student/addCreditCard" class="form" method="POST" enctype="multipart/form-data">
    <div class="form-grid">
        <div class="form-row">
            <label for="cardTitle">Card Title:</label>
            <input type="text" id="cardTitle" name="cardTitle" required>
        </div>
        <div class="form-row">
            <label for="cardNumber">Card Number:</label>
            <input type="text" id="cardNumber" name="cardNumber" required>
        </div>
        <div class="form-row">
            <label for="expiryDate">Expiry Date:</label>
            <input type="text" id="expiryDate" name="expiryDate" required>
        </div>
        <div class="form-row">
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>
        </div>
        <div class="form-row">
            <label for="name">Name on Card:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-row">
            <label for="surname">Surname on Card:</label>
            <input type="text" id="surname" name="surname" required>
        </div>
        <div class="form-row full-width">
            <button class="button-spec final" type="button" onclick="addPaymentMethod()">Submit</button>
        </div>
    </div>
</form>


    </div>
</div>
<div id="confirmModal" class="resModal">
    <div class="resModal-content">
        <span class="resClose" onclick="closeConfirmModal()">&times;</span>
        <p>Are you sure you want to delete this card?</p>
        <div class="btn-cont">
        <button id="confirmDelete" type="button">Yes</button>
        <button id="cancelDelete" type="button" onclick="closeConfirmModal()">Cancel</button>
        </div>
    </div>
</div>
<script>
    {literal}
    // Define cardsData as a JavaScript variable
    let cardsData = {/literal}{$cardsData}{literal};
    console.log('Raw cardsData:', cardsData);

    // Check if cardsData is already an array, if not, wrap it in an array
    let cards = [];
    if (Array.isArray(cardsData)) {
        cards = cardsData;
    } else if (cardsData && typeof cardsData === 'object') {
        cards = [cardsData]; // Wrap the single object in an array
    } else {
        console.error('Invalid cardsData format:', cardsData);
    }

    console.log('Parsed cards:', cards);
    console.log('Is cards an array?', Array.isArray(cards));

    function displayCards(cards) {
        const container = document.getElementById('cardsContainer');

        if (container) {
            if (cards.length === 0) {
                container.innerHTML = '<div class="container"><h1 class="noCards">You don\'t have any credit cards memorized</h1></div>';
            } else {
                container.innerHTML = ''; // Clear container before adding new cards
                cards.forEach(card => {
                    const cardElement = document.createElement('div');
                    cardElement.className = 'review';
                    let cardNumber = card.number.replace(/\s/g, '');
                     let groups = cardNumber.match(/.{1,4}/g);
                     let result = "";

                     for(let i = 0; i < groups.length; i++) {
                         result += `<p>${groups[i]}</p>\n`;
                        }

                    let buttonHTML = '';
                    if (card.isMain) {
                        buttonHTML = `<h2> Main </h2>`;
                    } else {
                        buttonHTML = `<button class="button-spec" onclick="makeMain('${card.number}')"> Make Main </button>`;
                    }

                    cardElement.innerHTML = `
                        <h1 class="paymentTitle"> ${card.title} </h1> <!-- Title of the card -->
                        <div class="paymentGrid">
                            <div class="divPAY1">
                            <div class="container1">
        <div class="card1">
            <div class="card-inner">
                <div class="front">
                    <img src="https://i.ibb.co/PYss3yv/map.png" class="map-img">
                    <div class="row1">
                        <img src="https://i.ibb.co/G9pDnYJ/chip.png" width="60px">
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
                    <div class="row1 signature">
                        <p>CUSTOMER SIGNATURE</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
                            </div>
                            <div class="divPAY2">
                                ${buttonHTML}
                            </div>
                        </div>
                        <button class="button-spec little button-delete" onclick="openConfirmModal('${card.number}')">-</button>
                    `;
                                

                    container.appendChild(cardElement);
                });
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
        const confirmYesButton = document.getElementById('confirmDelete');
        confirmYesButton.setAttribute('data-card-number', cardNumber);
        document.getElementById('confirmModal').style.display = "block";
    }

    // Function to close the confirm deletion modal
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = "none";
    }

    // Function to add a payment method
    function addPaymentMethod() {
        const cardTitle = document.getElementById('cardTitle').value;
        const cardNumber = document.getElementById('cardNumber').value;
        const expiryDate = document.getElementById('expiryDate').value;
        const cvv = document.getElementById('cvv').value;
        const name = document.getElementById('name').value;
        const surname = document.getElementById('surname').value;

        // Logic to handle the new card information (e.g., make an API call to save the card)
        const newCard = {
            title: cardTitle,
            number: cardNumber,
            expiryDate: expiryDate,
            cvv: cvv,
            name: name,
            surname: surname,
            isMain: false
        };

        // Update the cards array and re-display
        cards.push(newCard);
        displayCards(cards);

        // Close the modal after submitting the form
        closeModal();
    }

    // Function to delete a card
    function deleteCard(cardNumber) {
        // Send a request to the server to delete the card
        fetch(`/UniRent/Student/deleteCreditCard/${cardNumber}`, {
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

    // Function to make a card the main card
    function makeMain(cardNumber) {
        let mainCard = null;
        const otherCards = [];
        
        cards.forEach(card => {
            if (card.number === cardNumber) {
                card.isMain = true;
                mainCard = card;
            } else {
                card.isMain = false;
                otherCards.push(card);
            }
        });
         console.log('Main card:', mainCard);
         console.log('Other card:', otherCards);
        if (mainCard) {
            cards = [mainCard, ...otherCards];
        } else {
            console.error('Card not found:', cardNumber);
        }
        
        console.log('Updated cards:', cards);
        displayCards(cards);
    }

    // Event listener for the confirmation button
    document.getElementById('confirmDelete').addEventListener('click', function() {
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
    {/literal}
</script>






<!-- footer section start -->
      <div class="footer_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-md-4">
                  <h3 class="footer_text">About Us</h3>
                  <p class="lorem_text">Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web</p>
               </div>
               <hr></hr>
               <div class="col-md-4">
                  <h3 class="footer_text">Useful Links</h3>
                  <div class="footer_menu">
                     <ul>
                        <li><a href="/UniRent/Student/home">Home</a></li>
                        <li><a href="/UniRent/Student/about">About Us</a></li>
                        <li><a href="/UniRent/Student/contact">Contact Us</a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div>

      <!-- footer section end -->
<script src="/UniRent/Smarty/js/jquery.min.js"></script>
      <script src="/UniRent/Smarty/js/popper.min.js"></script>
      <script src="/UniRent/Smarty/js/bootstrap.bundle.min.js"></script>
      <script src="/UniRent/Smarty/js/jquery-3.0.0.min.js"></script>
      <script src="/UniRent/Smarty/js/plugin.js"></script>
      <!-- sidebar -->
      <script src="/UniRent/Smarty/js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="/UniRent/Smarty/js/custom.js"></script>
      <div class="modal" id="myModal">
      <div class"container-fluid">
      <div class="card">
         <svg xml:space="preserve" viewBox="0 0 122.88 122.25" y="0px" x="0px" id="cookieSvg" version="1.1"><g><path d="M101.77,49.38c2.09,3.1,4.37,5.11,6.86,5.78c2.45,0.66,5.32,0.06,8.7-2.01c1.36-0.84,3.14-0.41,3.97,0.95 c0.28,0.46,0.42,0.96,0.43,1.47c0.13,1.4,0.21,2.82,0.24,4.26c0.03,1.46,0.02,2.91-0.05,4.35h0v0c0,0.13-0.01,0.26-0.03,0.38 c-0.91,16.72-8.47,31.51-20,41.93c-11.55,10.44-27.06,16.49-43.82,15.69v0.01h0c-0.13,0-0.26-0.01-0.38-0.03 c-16.72-0.91-31.51-8.47-41.93-20C5.31,90.61-0.73,75.1,0.07,58.34H0.07v0c0-0.13,0.01-0.26,0.03-0.38 C1,41.22,8.81,26.35,20.57,15.87C32.34,5.37,48.09-0.73,64.85,0.07V0.07h0c1.6,0,2.89,1.29,2.89,2.89c0,0.4-0.08,0.78-0.23,1.12 c-1.17,3.81-1.25,7.34-0.27,10.14c0.89,2.54,2.7,4.51,5.41,5.52c1.44,0.54,2.2,2.1,1.74,3.55l0.01,0 c-1.83,5.89-1.87,11.08-0.52,15.26c0.82,2.53,2.14,4.69,3.88,6.4c1.74,1.72,3.9,3,6.39,3.78c4.04,1.26,8.94,1.18,14.31-0.55 C99.73,47.78,101.08,48.3,101.77,49.38L101.77,49.38z M59.28,57.86c2.77,0,5.01,2.24,5.01,5.01c0,2.77-2.24,5.01-5.01,5.01 c-2.77,0-5.01-2.24-5.01-5.01C54.27,60.1,56.52,57.86,59.28,57.86L59.28,57.86z M37.56,78.49c3.37,0,6.11,2.73,6.11,6.11 s-2.73,6.11-6.11,6.11s-6.11-2.73-6.11-6.11S34.18,78.49,37.56,78.49L37.56,78.49z M50.72,31.75c2.65,0,4.79,2.14,4.79,4.79 c0,2.65-2.14,4.79-4.79,4.79c-2.65,0-4.79-2.14-4.79-4.79C45.93,33.89,48.08,31.75,50.72,31.75L50.72,31.75z M119.3,32.4 c1.98,0,3.58,1.6,3.58,3.58c0,1.98-1.6,3.58-3.58,3.58s-3.58-1.6-3.58-3.58C115.71,34.01,117.32,32.4,119.3,32.4L119.3,32.4z M93.62,22.91c2.98,0,5.39,2.41,5.39,5.39c0,2.98-2.41,5.39-5.39,5.39c-2.98,0-5.39-2.41-5.39-5.39 C88.23,25.33,90.64,22.91,93.62,22.91L93.62,22.91z M97.79,0.59c3.19,0,5.78,2.59,5.78,5.78c0,3.19-2.59,5.78-5.78,5.78 c-3.19,0-5.78-2.59-5.78-5.78C92.02,3.17,94.6,0.59,97.79,0.59L97.79,0.59z M76.73,80.63c4.43,0,8.03,3.59,8.03,8.03 c0,4.43-3.59,8.03-8.03,8.03s-8.03-3.59-8.03-8.03C68.7,84.22,72.29,80.63,76.73,80.63L76.73,80.63z M31.91,46.78 c4.8,0,8.69,3.89,8.69,8.69c0,4.8-3.89,8.69-8.69,8.69s-8.69-3.89-8.69-8.69C23.22,50.68,27.11,46.78,31.91,46.78L31.91,46.78z M107.13,60.74c-3.39-0.91-6.35-3.14-8.95-6.48c-5.78,1.52-11.16,1.41-15.76-0.02c-3.37-1.05-6.32-2.81-8.71-5.18 c-2.39-2.37-4.21-5.32-5.32-8.75c-1.51-4.66-1.69-10.2-0.18-16.32c-3.1-1.8-5.25-4.53-6.42-7.88c-1.06-3.05-1.28-6.59-0.61-10.35 C47.27,5.95,34.3,11.36,24.41,20.18C13.74,29.69,6.66,43.15,5.84,58.29l0,0.05v0h0l-0.01,0.13v0C5.07,73.72,10.55,87.82,20.02,98.3 c9.44,10.44,22.84,17.29,38,18.1l0.05,0h0v0l0.13,0.01h0c15.24,0.77,29.35-4.71,39.83-14.19c10.44-9.44,17.29-22.84,18.1-38l0-0.05 v0h0l0.01-0.13v0c0.07-1.34,0.09-2.64,0.06-3.91C112.98,61.34,109.96,61.51,107.13,60.74L107.13,60.74z M116.15,64.04L116.15,64.04 L116.15,64.04L116.15,64.04z M58.21,116.42L58.21,116.42L58.21,116.42L58.21,116.42z"></path></g></svg>
         <p class="cookieHeading">We use cookies.</p>
         <p class="cookieDescription">We use cookies to ensure that we give you the best experience on our website. Please Activate Them.</p>
         </div> 
      </div>
      </div>
    <script>
            function on() {
            if (!navigator.cookieEnabled) {
               document.getElementById("myModal").style.display = "flex";
            }
            }
            function off() {
               document.getElementById("myModal").style.display = "none";
               }
         </script>
   </body>