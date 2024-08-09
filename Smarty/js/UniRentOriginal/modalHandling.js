function removeBanModal(userId, type) {
    document.getElementById('removeBanButton').href = '/UniRent/Admin/active/' + type + '/' + userId;
    $('#removeBanModal').modal('show');
}
function BanModal(userId, type, reportId) {
    document.getElementById('banButton').href = '/UniRent/Admin/ban/' + type + '/' + userId +'/' + reportId;
    $('#banModal').modal('show');
}
function on() {
    if (!navigator.cookieEnabled) {
       document.getElementById("myModal").style.display = "flex";
    }
}
function off() {
    document.getElementById("myModal").style.display = "none";
}
    var successRequestModal = document.getElementById("successRequestModal");
    var closeSpan = document.getElementById("closeSpan");
    var closeButton = document.getElementById("closeButton");
    

    if (requestSuccess !== 'null') {
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

    window.onclick = function(event) {
        if (event.target == successRequestModal) {
            successRequestModal.style.display = "none";
        }
    }