
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
    const reportButtons = document.querySelectorAll('button.delete_button');
    const reportModal = document.getElementById("reportModal");
    const reportSpan = document.querySelector(".resClose");
    const cancelReportBtn = document.getElementById("cancelReport");
    const reportForm = document.getElementById("reportForm");
    const reportReason = document.getElementById("reportReason");
        
    if ( reportModal ) {
        reportButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const reviewId = button.getAttribute('data-review-id'); // Get the owner ID from the button
                reportForm.action = "/UniRent/Report/makeReport/" + reviewId + "/Review"; // Dynamically set the form action
                reportModal.style.display = "block";
            });
        });
        
        // When the user clicks on <span> (x), close the modal
        reportSpan.onclick = function() {
            reportModal.style.display = "none";
        };
        
        // When the user clicks on the cancel button, close the modal
        cancelReportBtn.onclick = function() {
            cancelReport();
        };
        
    }
    /**
     * This is used to check that the report reason is not empty before enabling the submit button
     */
    function checkInput() {
        const submitBtn = document.getElementById("confirmReport");
        const reportReason = document.getElementById("reportReason");
        if (reportReason.value.trim() !== "") {
            submitBtn.disabled = false;
            submitBtn.classList.remove("disabled");
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add("disabled");
        }
    }
    
    /**
     * This is used to cancel the report and closing the modal by clearing the report reason and disabling the submit button
     */
    function cancelReport() {
        const reportReason = document.getElementById("reportReason");
        const submitBtn = document.getElementById("confirmReport");
        reportReason.value = '';
        submitBtn.disabled = true;
        submitBtn.classList.add("disabled");
        closeReportModal();
    }
    
    /**
     * This is used to close the report modal
     */
    function closeReportModal() {
        document.getElementById("reportModal").style.display = "none";
    }

    /**
     * This is used to deactivate the accommodation
     */
    function deactivateFunct() {
        window.location.href = "/UniRent/Accommodation/deactivate/{$accommodation->getIdAccommodation()}";
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
        confirmDeleteBtn.onclick = function() {
            window.location.href = "/UniRent/Accommodation/delete/{$accommodation->getIdAccommodation()}";
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
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == deleteConfirmModal) {
            deleteConfirmModal.style.display = "none";
        }
        if (event.target == notDeletableModal) {
            notDeletableModal.style.display = "none";
        }
        if (event.target == reportModal) {
            reportModal.style.display = "none";
        }
        if (event.target == successRequestModal) {
            successRequestModal.style.display = "none";
        }
        if (event.target == contactModal) {
            contactModal.style.display = "none";
        }
        if (event.target == successModal) {
            successModal.style.display = "none";
            window.location.href = currentPage;
        }
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
    }
