
/**
 * 
 * function to display the remove ban modal to the admin
 * @param {int} userId 
 * @param {string} type 
 */
function removeBanModal(userId, type) {
    document.getElementById('removeBanButton').href = '/UniRent/Admin/activate/' + type + '/' + userId;
    $('#removeBanModal').modal('show');
}
/**
 * 
 * function to display the ban modal to the admin
 * @param {int} userId 
 * @param {string} type 
 * @param {int} reportId 
 */
function BanModal(userId, type, reportId) {
    document.getElementById('banButton').href = '/UniRent/Admin/ban/' + type + '/' + userId +'/' + reportId;
    $('#banModal').modal('show');
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
    
    if ( successRequestModal ) {
        console.log(successRequestModal);
        if ( requestSuccess !== 'null') {
            successRequestModal.style.display = "block";
        } else {
            successRequestModal.style.display = "none";
        }
        closeSpan.onclick = function() {
            successRequestModal.style.display = "none";
            window.location.href = currentPage;
        }

        closeButton.onclick = function() {
            successRequestModal.style.display = "none";
            window.location.href = currentPage;
        }
    }

    /**
     * This is used to display the contact modal when the contact button is clicked
    */
    var contactModal = document.getElementById("contactModal");
    var contactBtn = document.getElementById("contactBtn");
    var contactClose = document.getElementById("contactClose");
    if ( contactModal ) {
        contactBtn.onclick = function(event) {
            event.preventDefault();
            contactModal.style.display = "block";
        }

        contactClose.onclick = function() {
            contactModal.style.display = "none";
        }

    }
    /**
     * This is used to display the success modal when the some operation is made. This is used also for error messages
     */
    var successModal = document.getElementById("successModal");
    var successClose = document.getElementById("successClose");
    var closeSuccess = document.getElementById("closeSuccess");
    if ( successModal ) {
        if (modalSuccess !== '') {
            successModal.style.display = "block";
        } else {
            successModal.style.display = "none";
        }
        successClose.onclick = function() {
            successModal.style.display = "none";
            window.location.href = currentPage;
        }

        closeSuccess.onclick = function() {
            successModal.style.display = "none";
            window.location.href = currentPage;
        }
    }
    /**
     * This is used to display the report modal when the report button is clicked
     */
    
    const reportModalUser = document.getElementById("reportModalUser");
    const reportFormUser = document.getElementById("reportFormUser");
    const reportReasonUser = document.getElementById("reportReasonUser");
    const reportConfirmUser = document.getElementById("confirmReportUser");

    const reportButtonsReview = document.querySelectorAll('button.delete_button');
    const reportModalReview = document.getElementById("reportModalReview");
    const reportFormReview = document.getElementById("reportFormReview");
    const reportReasonReview = document.getElementById("reportReasonReview");
    const reportConfirmReview = document.getElementById("confirmReportReview");

        
    if (reportModalReview) {
        reportButtonsreview.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const reviewId = button.getAttribute('data-review-id'); // Get the owner ID from the button
                reportFormReview.action = "/UniRent/Report/makeReport/" + reviewId + "/Review"; // Dynamically set the form action
                reportModalReview.style.display = "block";
            });
        });
        
    }
    /**
     * This is used to check that the report reason is not empty before enabling the submit button
     */
    function checkInputReview() {
        let reportReason = reportReasonReview ? reportReasonReview : reportReasonUser;
        let reportConfirm = reportConfirmReview ? reportConfirmReview : reportConfirmUser;
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
        let reportConfirm = reportConfirmReview ? reportConfirmReview : reportConfirmUser;
        reportReason.value = '';
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

    if ( deactivateModal ) {
        deactivateBtn.onclick = function(event) {
            event.preventDefault(); // Prevent the default action (navigation)
            deactivateModal.style.display = "block";
        }
        deactivateClose.onclick = function() {
            deactivateModal.style.display = "none";
        }
        cancelDeactivate.onclick = function() {
            deactivateModal.style.display = "none";
        }
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
        if (typeof postedReviews !== 'undefined') {
            var deleteButtons = document.querySelectorAll('.delete_button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent the default action (navigation)
                    const reviewId = event.target.getAttribute('data-review-id');
                    deleteConfirmModal.style.display = "block";
    
                    // Set the delete action URL with the review ID
                    confirmDeleteBtn.onclick = function() {
                        window.location.href = "/UniRent/Review/delete/" + reviewId;
                    };
                });
            });
        }
        confirmDeleteBtn.onclick = function() {
            switch (deleteVariable) {
                case 'Accommodation':
                    window.location.href = "/UniRent/Accommodation/delete/" + accommodationId;
                    break;
                case 'Owner':
                    window.location.href = "/UniRent/Owner/deleteProfile";
                    break;
                case 'Student':
                    window.location.href = "/UniRent/Student/deleteProfile";
                    break;
                case 'Visit':
                    window.location.href = "/UniRent/Visit/delete/" + visitId;
                default:
                    break;
            }
            deleteConfirmModal.style.display = "none";
        }
        cancelDeleteBtn.onclick = function() {
            deleteConfirmModal.style.display = "none";
        }
        deleteConfirmSpan.onclick = function() {
            deleteConfirmModal.style.display = "none";
        }
        deleteModalOpen.onclick = function(event) {
            event.preventDefault(); // Prevent the default action (navigation)
            if (typeof deletable !== 'undefined' && !deletable) {
                notDeletableModal.style.display = "block";
            } else {
                deleteConfirmModal.style.display = "block";
            }
        }
        if (notDeletableModal) {
            notDeletableClose.onclick = function() {
                notDeletableModal.style.display = "none";
            }

            // When the user clicks on the understood button, close the modal
            understoodBtn.onclick = function() {
                notDeletableModal.style.display = "none";
            }
        }
    }

    // Get the modal
    var loginModal = document.getElementById("loginModal");
    // Get the <span> element that closes the modal
    var loginClose = document.getElementById("loginClose");

    if ( loginModal ) {
        // When the user clicks the button, open the modal 
        function showModal(event) {
            event.preventDefault();
            loginModal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        loginClose.onclick = function() {
            loginModal.style.display = "none";
        }
    }
    if ( document.getElementById("emailForm") ) {ß
        function emailFormOpen() {
            document.getElementById("emailForm").style.display = "block";
        }
        document.getElementById('emailForm').addEventListener('submit', function(event) {
            document.getElementById('emailForm').style.display = 'none';
        });
    }
    const modalRev = document.getElementById('revModal');
    if (modalRev) {
        var reviewButton = document.getElementById("reviewButton");
    
        // Add event listener to reviewButton if it exists
        if (reviewButton) {
            reviewButton.addEventListener('click', function(event) {
                document.getElementById('revModal').style.display = 'grid';
            });
        }
        const closeModalRev = document.querySelector('#revClose');
        const cancelBut = document.querySelector('#CancelBut');
        
        if (closeModalRev) {
            closeModalRev.onclick = () => {
                modalRev.style.display = 'none';
            };
        }
        
        if (cancelBut) {
            cancelBut.onclick = () => {
                modalRev.style.display = 'none';
            };
        }
    }
    const editModal = document.getElementById('editModal');
    if (typeof postedReviews !== 'undefined') {
        const editButtons = document.querySelectorAll('.edit_button');
            editButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    const reviewId = event.target.getAttribute('data-review-id');
                    const review = reviews.find(r => r.id == reviewId);
                    if (review) {
                        // Populate form fields with review data
                        document.getElementById('editReviewForm').action = '/UniRent/Review/edit/' + review.id;
                        document.getElementById('editReviewForm').method = 'POST';
                        document.getElementById('reviewTitle').value = review.title;
                        document.getElementById('reviewContent').value = review.content;
                        document.querySelector('input[name="rate"][value="' + review.stars + '"]').checked = true;

                        // Display the modal
                        document.getElementById('editModal').style.display = 'grid';
                    }
                });
            });
           
            const closeEditModal = document.querySelector('#editClose');
            const cancelEditBut = document.querySelector('#CancelEditBut');
            cancelEditBut.onclick = () => {
                editModal.style.display = 'none';
            }

            closeEditModal.onclick = () => {
                editModal.style.display = 'none';
            }


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

        denyBtn.onclick = function(event) {
            event.preventDefault(); // Prevent the default action (navigation)
            denyModal.style.display = "block";
        }
        denyClose.onclick = function() {
            denyModal.style.display = "none";
        }
        cancelDeny.onclick = function() {
            denyModal.style.display = "none";
        }
    }

    var acceptModal = document.getElementById("acceptModal");
    if (acceptModal) {
        var acceptClose = document.getElementById("acceptClose");
        var cancelAccept = document.getElementById("cancelAccept");
        var acceptBtn = document.getElementById("acceptBtn");
        acceptBtn.onclick = function(event) {
            event.preventDefault(); // Prevent the default action (navigation)
            acceptModal.style.display = "block";
         }
         acceptClose.onclick = function() {
            acceptModal.style.display = "none";
         }
          cancelAccept.onclick = function() {
              acceptModal.style.display = "none";
          }
    }

    var successDeleteModal = document.getElementById("successDeleteModal");
    if (successDeleteModal) {
            var successDeleteClose = document.getElementById("successDeleteClose");
            var closesuccessDeleteModal = document.getElementById("closesuccessDeleteModal");

            function showsuccessDeleteModal() {
               if (successDelete != 'null') {
                     successDeleteModal.style.display = "block";
               }
            }
            successDeleteClose.onclick = function() {
               successDeleteModal.style.display = "none";
               window.location.href = currentPage;
            }
            closesuccessDeleteModal.onclick = function() {
               successDeleteModal.style.display = "none";
               window.location.href = currentPage;
            }
            showsuccessDeleteModal();
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
        successDeleteModal
    ];
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        modals.forEach(modal => {
            if (event.target == modal) {
                modal.style.display = "none";
                if (modal == successModal) {
                    window.location.href = currentPage;
                }
            }
        });
        
    }
