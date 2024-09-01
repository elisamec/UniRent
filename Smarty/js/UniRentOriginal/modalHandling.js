/**
 *
 * function to display the remove ban modal to the admin
 * @param {int} userId
 * @param {string} type
 */
function removeBanModal(userId, type) {
  document.getElementById("removeBanButton").href =
    "/UniRent/Admin/activate/" + type + "/" + userId;
  $("#removeBanModal").modal("show");
}
/**
 *
 * function to display the ban modal to the admin
 * @param {int} userId
 * @param {string} type
 * @param {int} reportId
 */
function BanModal(userId, type, reportId) {
  document.getElementById("banButton").href =
    "/UniRent/Admin/ban/" + type + "/" + userId + "/" + reportId;
  $("#banModal").modal("show");
}
/**
 * functions to display the cookie modal if cookies are disabled
 */
function on() {
  if (!navigator.cookieEnabled) {
    document.getElementById("myModal").style.display = "flex";
  }
}
function off() {
  document.getElementById("myModal").style.display = "none";
}

/**
 * This is used to display the success modal when a request for ban removal is successful
 */
var successRequestModal = document.getElementById("successRequestModal");
var closeSpan = document.getElementById("closeSpan");
var closeButton = document.getElementById("closeButton");

if (successRequestModal) {
  console.log(successRequestModal);
  if (requestSuccess !== "null") {
    successRequestModal.style.display = "block";
  } else {
    successRequestModal.style.display = "none";
  }
  closeSpan.onclick = function () {
    successRequestModal.style.display = "none";
    window.location.href = currentPage;
  };

  closeButton.onclick = function () {
    successRequestModal.style.display = "none";
    window.location.href = currentPage;
  };
}

/**
 * This is used to display the contact modal when the contact button is clicked
 */
var contactModal = document.getElementById("contactModal");
var contactBtn = document.getElementById("contactBtn");
var contactClose = document.getElementById("contactClose");
if (contactModal) {
  contactBtn.onclick = function (event) {
    event.preventDefault();
    contactModal.style.display = "block";
  };

  contactClose.onclick = function () {
    contactModal.style.display = "none";
  };
}
/**
 * This is used to display the success modal when the some operation is made. This is used also for error messages
 */
var successModal = document.getElementById("successModal");
var successClose = document.getElementById("successClose");
var closeSuccess = document.getElementById("closeSuccess");
if (successModal) {
  if (modalSuccess !== "") {
    successModal.style.display = "block";
  } else {
    successModal.style.display = "none";
  }
  successClose.onclick = function () {
    successModal.style.display = "none";
    window.location.href = currentPage;
  };

  closeSuccess.onclick = function () {
    successModal.style.display = "none";
    window.location.href = currentPage;
  };
}
/**
 * This is used to display the report modal when the report button is clicked
 */

const reportModalUser = document.getElementById("reportModalUser");
const reportFormUser = document.getElementById("reportFormUser");
const reportReasonUser = document.getElementById("reportReasonUser");
const reportConfirmUser = document.getElementById("confirmReportUser");

const reportButtonsReview = document.querySelectorAll("button.delete_button");
const reportModalReview = document.getElementById("reportModalReview");
const reportFormReview = document.getElementById("reportFormReview");
const reportReasonReview = document.getElementById("reportReasonReview");
const reportConfirmReview = document.getElementById("confirmReportReview");

if (reportModalReview) {
  reportButtonsreview.forEach((button) => {
    button.addEventListener("click", (event) => {
      event.preventDefault();
      const reviewId = button.getAttribute("data-review-id"); // Get the owner ID from the button
      reportFormReview.action =
        "/UniRent/Report/makeReport/" + reviewId + "/Review"; // Dynamically set the form action
      reportModalReview.style.display = "block";
    });
  });
}
/**
 * This is used to check that the report reason is not empty before enabling the submit button
 */
function checkInputReview() {
  let reportReason = reportReasonReview ? reportReasonReview : reportReasonUser;
  let reportConfirm = reportConfirmReview
    ? reportConfirmReview
    : reportConfirmUser;
  if (reportReason.value.trim() !== "") {
    reportConfirm.disabled = false;
    reportConfirm.classList.remove("disabled");
  } else {
    reportConfirm.disabled = true;
    reportConfirm.classList.add("disabled");
  }
}

/**
 * This is used to cancel the report and closing the modal by clearing the report reason and disabling the submit button
 */
function cancelReportReview() {
  let reportModal = reportModalReview ? reportModalReview : reportModalUser;
  let reportReason = reportReasonReview ? reportReasonReview : reportReasonUser;
  let reportConfirm = reportConfirmReview
    ? reportConfirmReview
    : reportConfirmUser;
  reportReason.value = "";
  reportConfirm.disabled = true;
  reportConfirm.classList.add("disabled");
  reportModal.style.display = "none";
}
/**
 * This is used to open the report modal when the report button is clicked
 */
function openReportModalUser() {
  reportModalUser.style.display = "block";
}

/**
 * This is used to deactivate the accommodation
 */
function deactivateFunct() {
  window.location.href = "/UniRent/Accommodation/deactivate/" + accommodationId;
  deactivateModal.style.display = "none";
}

/**
 * This is used to display the deactivate modal when the deactivate button is clicked
 */
var deactivateClose = document.getElementById("deactivateClose");
var cancelDeactivate = document.getElementById("cancelDeactivate");
var deactivateBtn = document.getElementById("deactivateLink");
var deactivateModal = document.getElementById("deactivateModal");

if (deactivateModal) {
  deactivateBtn.onclick = function (event) {
    event.preventDefault(); // Prevent the default action (navigation)
    deactivateModal.style.display = "block";
  };
  deactivateClose.onclick = function () {
    deactivateModal.style.display = "none";
  };
  cancelDeactivate.onclick = function () {
    deactivateModal.style.display = "none";
  };
}

/**
 * This is used to display the confirm delete modal when the delete button is clicked and proceed to delete
 */
var deleteConfirmModal = document.getElementById("deleteConfirmModal");
var confirmDeleteBtn = document.getElementById("confirmDelete");
var cancelDeleteBtn = document.getElementById("cancelDelete");
var deleteModalOpen = document.getElementById("deleteLink");
var deleteConfirmSpan = document.getElementById("deleteConfirmClose");
var notDeletableModal = document.getElementById("notDeletableModal");
var notDeletableClose = document.getElementById("notDeletableClose");
var understoodBtn = document.getElementById("understood");

if (deleteConfirmModal) {
  if (typeof postedReviews !== "undefined") {
    var deleteButtons = document.querySelectorAll(".delete_button");
    document.querySelectorAll('a.disabled').forEach(function(link) {
        link.addEventListener('click', function(event) {
          event.preventDefault();
        });
      });
    deleteButtons.forEach((button) => {
      button.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent the default action (navigation)
        const reviewId = event.target.getAttribute("data-review-id");
        deleteConfirmModal.style.display = "block";

        // Set the delete action URL with the review ID
        confirmDeleteBtn.onclick = function () {
          window.location.href = "/UniRent/Review/delete/" + reviewId;
        };
      });
    });
  }
  confirmDeleteBtn.onclick = function () {
    switch (deleteVariable) {
      case "Accommodation":
        window.location.href =
          "/UniRent/Accommodation/delete/" + accommodationId;
        break;
      case "Owner":
        window.location.href = "/UniRent/Owner/deleteProfile";
        break;
      case "Student":
        window.location.href = "/UniRent/Student/deleteProfile";
        break;
      case "Visit":
        window.location.href = "/UniRent/Visit/delete/" + visitId;
      default:
        break;
    }
    deleteConfirmModal.style.display = "none";
  };
  cancelDeleteBtn.onclick = function () {
    deleteConfirmModal.style.display = "none";
  };
  deleteConfirmSpan.onclick = function () {
    deleteConfirmModal.style.display = "none";
  };
  deleteModalOpen.onclick = function (event) {
    event.preventDefault(); // Prevent the default action (navigation)
    if (typeof deletable !== "undefined" && !deletable) {
      notDeletableModal.style.display = "block";
    } else {
      deleteConfirmModal.style.display = "block";
    }
  };
  if (notDeletableModal) {
    notDeletableClose.onclick = function () {
      notDeletableModal.style.display = "none";
    };

    // When the user clicks on the understood button, close the modal
    understoodBtn.onclick = function () {
      notDeletableModal.style.display = "none";
    };
  }
}

// Get the modal
var loginModal = document.getElementById("loginModal");
// Get the <span> element that closes the modal
var loginClose = document.getElementById("loginClose");

if (loginModal) {
  // When the user clicks the button, open the modal
  function showModal(event) {
    event.preventDefault();
    loginModal.style.display = "block";
  }

  // When the user clicks on <span> (x), close the modal
  loginClose.onclick = function () {
    loginModal.style.display = "none";
  };
}
if (document.getElementById("emailForm")) {
  ß;
  function emailFormOpen() {
    document.getElementById("emailForm").style.display = "block";
  }
  document
    .getElementById("emailForm")
    .addEventListener("submit", function (event) {
      document.getElementById("emailForm").style.display = "none";
    });
}
const modalRev = document.getElementById("revModal");
if (modalRev) {
  var reviewButton = document.getElementById("reviewButton");

  // Add event listener to reviewButton if it exists
  if (reviewButton) {
    reviewButton.addEventListener("click", function (event) {
      document.getElementById("revModal").style.display = "grid";
    });
  }
  const closeModalRev = document.querySelector("#revClose");
  const cancelBut = document.querySelector("#CancelBut");

  if (closeModalRev) {
    closeModalRev.onclick = () => {
      modalRev.style.display = "none";
    };
  }

  if (cancelBut) {
    cancelBut.onclick = () => {
      modalRev.style.display = "none";
    };
  }
}
const editModal = document.getElementById("editModal");
if (typeof postedReviews !== "undefined") {
  const editButtons = document.querySelectorAll(".edit_button");
  editButtons.forEach((button) => {
    button.addEventListener("click", (event) => {
      const reviewId = event.target.getAttribute("data-review-id");
      const review = reviews.find((r) => r.id == reviewId);
      if (review) {
        // Populate form fields with review data
        document.getElementById("editReviewForm").action =
          "/UniRent/Review/edit/" + review.id;
        document.getElementById("editReviewForm").method = "POST";
        document.getElementById("reviewTitle").value = review.title;
        document.getElementById("reviewContent").value = review.content;
        document.querySelector(
          'input[name="rate"][value="' + review.stars + '"]'
        ).checked = true;

        // Display the modal
        document.getElementById("editModal").style.display = "grid";
      }
    });
  });

  const closeEditModal = document.querySelector("#editClose");
  const cancelEditBut = document.querySelector("#CancelEditBut");
  cancelEditBut.onclick = () => {
    editModal.style.display = "none";
  };

  closeEditModal.onclick = () => {
    editModal.style.display = "none";
  };
}

/**
 * This is used to display the accept modal when the accept button is clicked for the reservations
 */
function acceptFunct() {
  window.location.href = "/UniRent/Reservation/accept/" + reservationId;
  acceptModal.style.display = "none";
}

/**
 * This is used to display the deny modal when the deny button is clicked for the reservations
 */
function denyFunct() {
  window.location.href = "/UniRent/Reservation/deny/" + reservationId;
  denyModal.style.display = "none";
}
var denyModal = document.getElementById("denyModal");
if (denyModal) {
  var denyClose = document.getElementById("denyClose");
  var cancelDeny = document.getElementById("cancelDeny");
  var denyBtn = document.getElementById("denyButton");

  denyBtn.onclick = function (event) {
    event.preventDefault(); // Prevent the default action (navigation)
    denyModal.style.display = "block";
  };
  denyClose.onclick = function () {
    denyModal.style.display = "none";
  };
  cancelDeny.onclick = function () {
    denyModal.style.display = "none";
  };
}

var acceptModal = document.getElementById("acceptModal");
if (acceptModal) {
  var acceptClose = document.getElementById("acceptClose");
  var cancelAccept = document.getElementById("cancelAccept");
  var acceptBtn = document.getElementById("acceptBtn");
  acceptBtn.onclick = function (event) {
    event.preventDefault(); // Prevent the default action (navigation)
    acceptModal.style.display = "block";
  };
  acceptClose.onclick = function () {
    acceptModal.style.display = "none";
  };
  cancelAccept.onclick = function () {
    acceptModal.style.display = "none";
  };
}

var successDeleteModal = document.getElementById("successDeleteModal");
if (successDeleteModal) {
  var successDeleteClose = document.getElementById("successDeleteClose");
  var closesuccessDeleteModal = document.getElementById(
    "closesuccessDeleteModal"
  );

  function showsuccessDeleteModal() {
    if (successDelete != "null") {
      successDeleteModal.style.display = "block";
    }
  }
  successDeleteClose.onclick = function () {
    successDeleteModal.style.display = "none";
    window.location.href = currentPage;
  };
  closesuccessDeleteModal.onclick = function () {
    successDeleteModal.style.display = "none";
    window.location.href = currentPage;
  };
  showsuccessDeleteModal();
}

var visitModal = document.getElementById("visitModal");
var confirmModal = document.getElementById("confirmModal");
var successVisitModal = document.getElementById("successVisitModal");
var visitEmptyModal = document.getElementById("visitEmptyModal");
if (visitModal && confirmModal && successVisitModal && visitEmptyModal) {
  var visitBtn = document.getElementById("visitBtn");

  // Event handler for Visit button
  visitBtn.onclick = function (event) {
    event.preventDefault();

    if (disabled) {
      // Show not visitable modal
      document.getElementById("notVisitableModal").style.display = "block";
    } else {
      if (
        !timeSlots ||
        typeof timeSlots !== "object" ||
        Object.keys(timeSlots).length === 0
      ) {
        // Show visit empty modal
        visitEmptyModal.style.display = "block";
      } else {
        // Check if already booked
        if ("{$booked}") {
          // Show confirmation modal
          confirmModal.style.display = "block";
        } else {
          // Show visit modal
          visitModal.style.display = "block";
        }
      }
    }
    // Close not visitable modal
    document.getElementById("notVisitableClose").onclick = function () {
      document.getElementById("notVisitableModal").style.display = "none";
    };

    document.getElementById("understoodVisit").onclick = function () {
      document.getElementById("notVisitableModal").style.display = "none";
    };

    // Close visit modal
    document.getElementById("visitClose").onclick = function () {
      visitModal.style.display = "none";
    };

    // Cancel visit action
    document.getElementById("cancelVisit").onclick = function () {
      visitModal.style.display = "none";
    };

    // Close confirmation modal
    document.getElementById("confirmClose").onclick = function () {
      confirmModal.style.display = "none";
    };

    // Cancel booking action
    document.getElementById("cancelBooking").onclick = function () {
      confirmModal.style.display = "none";
    };

    // Continue booking action
    document.getElementById("confirmBooking").onclick = function () {
      confirmModal.style.display = "none";
      visitModal.style.display = "block"; // Show visit modal after confirmation
    };

    // Close success visit modal
    document.getElementById("closeSuccessVisitModal").onclick = function () {
      successVisitModal.style.display = "none";
      window.location.href = currentPage;
    };
    document.getElementById("successVisitClose").onclick = function () {
      successVisitModal.style.display = "none";
      window.location.href = currentPage;
    };

    // Close visit empty modal
    document.getElementById("closeVisitEmptyModal").onclick = function () {
      visitEmptyModal.style.display = "none";
    };
    document.getElementById("visitEmptyClose").onclick = function () {
      visitEmptyModal.style.display = "none";
    };

    // Function to show success visit modal based on $successVisit value
    function showSuccessVisitModal() {
      if (successVisit !== "null") {
        successVisitModal.style.display = "block";
      }
    }

    // Call the function to check for $successVisit and show modal
    showSuccessVisitModal();

    // Optional: Populate time slots based on the selected day (if needed)
    var timeSelect = document.getElementById("time");
    var daySelect = document.getElementById("day");
    // Function to populate day select options
    function populateDays() {
      // Clear previous options
      daySelect.innerHTML =
        '<option value="" selected disabled>Select a day</option>';

      // Populate new options from timeSlots keys
      Object.keys(timeSlots).forEach(function (day) {
        var option = document.createElement("option");
        option.value = day;
        option.textContent = day.charAt(0).toUpperCase() + day.slice(1); // Capitalize the first letter
        daySelect.appendChild(option);
      });
    }

    // Ensure the initial day options are populated when the page loads
    populateDays();

    // Ensure the initial time slots are populated when the page loads if a day is selected
    var selectedDay = daySelect.value;
    populateTimeSlots(selectedDay);

    // Event listener for day change to populate time slots dynamically
    daySelect.addEventListener("change", function () {
      var selectedDay = this.value;
      populateTimeSlots(selectedDay);
    });

    // Function to populate time slots based on the selected day
    function populateTimeSlots(selectedDay) {
      // Clear previous options
      timeSelect.innerHTML =
        '<option value="" selected disabled>Select the time</option>';

      // Populate new options if timeSlots for selectedDay exist
      if (
        timeSlots[selectedDay] &&
        typeof timeSlots[selectedDay] === "object"
      ) {
        Object.keys(timeSlots[selectedDay]).forEach(function (key) {
          var option = document.createElement("option");
          option.value = timeSlots[selectedDay][key];
          option.textContent = timeSlots[selectedDay][key];
          timeSelect.appendChild(option);
        });
      }
    }
  };
}

var reserveModal = document.getElementById("reserveModal");
var notReservableModal = document.getElementById("notReservableModal");
var successReserveModal = document.getElementById("successReserveModal");

if (reserveModal && notReservableModal && successReserveModal) {
    // JavaScript function to populate the date select dropdown with dynamic year selection
    function populateYearSelect(selectedYear) {
        const select = document.getElementById("year");
        const currentYear = new Date().getFullYear();
    
        // Clear existing options
        select.innerHTML = "";
    
        // Add options for the next 4 years starting from current year
        for (let i = 0; i < 5; i++) {
            const year = currentYear + i;
            const option = document.createElement("option");
            option.value = year;
            option.text = year;
    
            // Check if this year matches the selectedYear
            if (year === selectedYear) {
                option.selected = true;
            }
    
            select.appendChild(option);
        }
    }
    
    
        // Call populateYearSelect function on document ready
        document.addEventListener("DOMContentLoaded", function() {
            populateYearSelect(selectedYear);
        });
    
    $(document).ready(function() {
        // Call populateDateSelect function on document ready
        populateDateSelect();
    
        // Event listener to handle change in select option
        $('#date').on('change', function() {
            populateDateSelect();
        });
    });
    
    
        // Get the button that opens the reserve modal
        var reserveBtn = document.getElementById("reserveBtn");
    
        // Get the buttons and elements for closing modals
        var reserveClose = document.getElementById("reserveClose");
        var notReservableClose = document.getElementById("notReservableClose");
        var understoodReserve = document.getElementById("understoodReserve");
        var cancelReserve = document.getElementById("cancelReserve");
    
        // Check if reservation is not possible
        var notReservable = '{$disabled}';
    
        // When the user clicks the reserve button
        reserveBtn.onclick = function(event) {
            event.preventDefault(); // Prevent the default action (navigation)
            if (!notReservable) {
                reserveModal.style.display = "block";
            } else {
                notReservableModal.style.display = "block";
            }
        }
    
        // When the user clicks on <span> to close the reserve modal
        reserveClose.onclick = function() {
            reserveModal.style.display = "none";
        }
    
        // When the user clicks on <span> to close the not reservable modal
        notReservableClose.onclick = function() {
            notReservableModal.style.display = "none";
        }
    
        // When the user clicks on 'Understood' in not reservable modal
        understoodReserve.onclick = function() {
            notReservableModal.style.display = "none";
        }
    
        // When the user clicks on 'Cancel' in the reserve modal
        cancelReserve.onclick = function() {
            reserveModal.style.display = "none";
        }
        // Function to show success modal if reservation was successful
    function showsuccessReserveModal() {
        if (successReserve != 'null') {
            successReserveModal.style.display = "block";
        }
    }
    document.getElementById("successReserveClose").onclick = function() {
        successReserveModal.style.display = "none";
        window.location.href= currentPage;
    }
    document.getElementById("closesuccessReserveModal").onclick = function() {
        successReserveModal.style.display = "none";
        window.location.href= currentPage;
    }

    // Call the function to check for success and show modal
    showsuccessReserveModal();
    }

    var payModal = document.getElementById("payModal");

    if (payModal) {
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof Inputmask !== 'undefined') {
                Inputmask().mask(document.querySelectorAll("input[data-inputmask]"));
            }
        });
    
        var payOpenBtn = document.getElementById("payOpenBtn");
    
        // Get the button that opens the modal
        var payBtn = document.getElementById("payBtn");
    
        // Get the <span> element that closes the modal
        var payClose = document.getElementById("payClose");
    
        var cancelPayBtn = document.getElementById("cancelPayBtn");
    
        // When the user clicks the button, open the modal 
        payOpenBtn.onclick = function(event) {
            event.preventDefault();
            payModal.style.display = "block";
        }
    
        // When the user clicks on <span> (x), close the modal
        payClose.onclick = function() {
            payModal.style.display = "none";
        }
        payBtn.onclick = function() {
            payModal.style.display = "none";
        }
        cancelPayBtn.onclick = function() {
            payModal.style.display = "none";
        }
        function createCreditCardRadioButtons(creditCardData) {
            var container = document.getElementById('creditCardContainer');
            container.innerHTML = ''; // Clear existing content
            creditCardData.forEach(function(card, index) {
                var radioBtn = document.createElement('input');
                radioBtn.type = 'radio';
                radioBtn.name = 'creditCardNumber';
                radioBtn.value = card.cardNumber;
                radioBtn.id = 'card' + index;
                if (card.main) {
                    radioBtn.checked = true;
                }
    
                var label = document.createElement('label');
                label.htmlFor = 'card' + index;
                label.textContent = card.cardName + ' (' + card.cardNumberHidden + ')';
    
                container.appendChild(radioBtn);
                container.appendChild(label);
                container.appendChild(document.createElement('br'));
    
                // Add event listener to hide new card form if this button is clicked
                radioBtn.addEventListener('click', function() {
                    document.getElementById('newCardContainer').style.display = 'none';
                    toggleRequired(false);
                });
            });
    
            // Add option for inserting a new card
            var newCardRadioBtn = document.createElement('input');
            newCardRadioBtn.type = 'radio';
            newCardRadioBtn.name = 'creditCard';
            newCardRadioBtn.value = 'newCard';
            newCardRadioBtn.id = 'newCard';
            newCardRadioBtn.onclick = function() {
                document.getElementById('newCardContainer').style.display = 'block';
                toggleRequired(true);
            };
    
            var newCardLabel = document.createElement('label');
            newCardLabel.htmlFor = 'newCard';
            newCardLabel.textContent = 'Insert a new card';
    
            container.appendChild(newCardRadioBtn);
            container.appendChild(newCardLabel);
            container.appendChild(document.createElement('br'));
        }
    
        // Call the function to create radio buttons
        createCreditCardRadioButtons(creditCardData);
    
        // Function to toggle the required attribute of the new card input fields
        function toggleRequired(isRequired) {
            var newCardFields = document.querySelectorAll('#newCardContainer input');
            newCardFields.forEach(function(field) {
                if (isRequired) {
                    field.setAttribute('required', 'required');
                } else {
                    field.removeAttribute('required');
                }
            });
        }
    }
    function showLoginRegistrationPopUp(event) {
        event.preventDefault(); // Previene l'invio del form
        var modal = document.getElementById("loginModal");
        modal.style.display = "block";
     }
     var successEditModal = document.getElementById("successEditModal");
     if (successEditModal) {
            var successEditClose = document.getElementById("successEditClose");
            var closesuccessEditModal = document.getElementById("closesuccessEditModal");

            function showsuccessEditModal() {
               if ('{$successEdit}' != 'null') {
                     successEditModal.style.display = "block";
               }
            }
            successEditClose.onclick = function() {
               successEditModal.style.display = "none";
               window.location.href = currentPage;
            }
            closesuccessEditModal.onclick = function() {
               successEditModal.style.display = "none";
               window.location.href = currentPage;
            }
            showsuccessEditModal();
     }

const modals = [
  deleteConfirmModal,
  notDeletableModal,
  reportModalReview,
  reportModalUser,
  successRequestModal,
  contactModal,
  successModal,
  loginModal,
  modalRev,
  editModal,
  acceptModal,
  denyModal,
  successDeleteModal,
  visitModal,
  confirmModal,
  successVisitModal,
  visitEmptyModal,
  reserveModal,
  notReservableModal,
  successReserveModal,
  payModal,
  loginModal,
  successEditModal
];
// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
  modals.forEach((modal) => {
    if (event.target == modal) {
      modal.style.display = "none";
      if (modal == successModal || modal == successRequestModal || modal == successDeleteModal || modal == successVisitModal || modal == successReserveModal || modal == successEditModal) {
        window.location.href = currentPage;
      }
    }
  });
};
