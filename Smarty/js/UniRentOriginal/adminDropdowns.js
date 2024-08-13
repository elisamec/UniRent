document.addEventListener('DOMContentLoaded', function () {
    const alertsDropdown = document.getElementById('alertsDropdown');
    const messagesDropdown = document.getElementById('messagesDropdown');

    async function fetchData() {
        try {
            const response = await fetch('/UniRent/Admin/getRequestAndReport');
            if (!response.ok) throw new Error('Network response was not ok');
            return await response.json();
        } catch (error) {
            console.error('Fetch error:', error);
        }
    }
    function getReviewData(report) {
        if (report.review !== null) {
            return `data-reviewid="${report.review.id}" data-title="${report.review.title}" data-description="${report.review.description}"`;
        } else {
            return '';
        }
    }

    function populateAlerts(reports) {
        const alertCountElement = document.getElementById('alertCount');
        const dropdownMenu = alertsDropdown.nextElementSibling; // The dropdown menu element
        // Assume dropdownMenu is correctly defined somewhere in your code
// Assume reports is an array of report objects

// Generate reviewData if review is not null


// Clear previous content
dropdownMenu.innerHTML = `
    <h6 class="dropdown-header">Reports</h6>
    ${reports.slice(0, 4).map(report => `
        <a class="dropdown-item d-flex align-items-center" href="#"
            data-toggle="modal" 
            data-target="#reportModal" 
            data-content="${report.description}" 
            data-subject="${report.usernameSubject}" 
            data-type="${report.type}" 
            data-id="${report.id}" 
            data-banDate="${report.banDate}"
            data-made="${report.made}"
            ${getReviewData(report)}>
            <div class="mr-3">
                <div class="icon-circle bg-danger">
                    <i class="fa fa-exclamation-triangle text-white"></i>
                </div>
            </div>
            <div>
                <div class="smallMessages text-gray-500">${new Date(report.made).toLocaleDateString()}</div>
                <span class="${report.banDate === null ? 'font-weight-bold report' : ''}">${report.description}</span>
            </div>
        </a>
    `).join('')}
    <a class="dropdown-item text-center smallMessages text-gray-500" href="/UniRent/Admin/readMoreReports">Show All Reports</a>
`;


        // Update alert count and visibility
        const reportCount = reports.filter(report => report.banDate === null).length;
        alertCountElement.textContent = reportCount;
        alertCountElement.style.display = reportCount > 0 ? 'inline-block' : 'none';
    }

    function populateMessages(requests) {
        const messageCountElement = document.getElementById('messageCount');
        const dropdownMenu = messagesDropdown.nextElementSibling; // The dropdown menu element

        // Clear previous content
        dropdownMenu.innerHTML = `
            <h6 class="dropdown-header">Support Requests</h6>
            ${requests.slice(0, 4).map(item => item.Request.map(request => `
                <a class="dropdown-item d-flex align-items-center" href="#" 
                    data-toggle="modal" 
                    data-target="#requestModal" 
                    data-content="${request.message}" 
                    data-author="${item.author}" 
                    data-topic="${request.topic}" 
                    data-id="${request.id}" 
                    data-status="${request.status}"
                    data-reply="${request.reply}">
                    <div class="${request.status === 0 ? 'font-weight-bold requestItem' : 'requestItem'}">
                        <div class="text-truncate">${request.message}</div>
                        <div class="smallMessages text-gray-500">${item.author} · ${request.topic}</div>
                    </div>
                </a>
            `).join('')).join('')}
            <a class="dropdown-item text-center smallMessages text-gray-500" href="/UniRent/Admin/readMoreSupportRequest">Read More Requests</a>
        `;

        // Update message count and visibility
        const messageCount = requests.flatMap(item => item.Request)
                                     .filter(request => request.status === 0).length;
        messageCountElement.textContent = messageCount;
        messageCountElement.style.display = messageCount > 0 ? 'inline-block' : 'none';
    }

    alertsDropdown.addEventListener('click', async function () {
        const data = await fetchData();
        if (data) populateAlerts(data.Reports);
    });

    messagesDropdown.addEventListener('click', async function () {
        const data = await fetchData();
        if (data) populateMessages(data.Requests);
    });

    // Update modal content and visibility based on request status
    $('#requestModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const content = button.data('content');
        const author = button.data('author');
        const topic = button.data('topic');
        const status = button.data('status');
        const showForm = status === 0; // Show form only if status is 0
        const requestId = button.data('id');
        const adminReply = button.data('reply');

        // Set modal content
        $('#requestContent').text(content);
        $('#requestAuthor').text(author);
        $('#requestTopic').text(topic);
        $('input[name="requestId"]').val(requestId);

        // Show or hide the reply form based on the request status
        if (showForm && topic !== 'Registration') {
            $('#replyContainer').show();
            $('#submitReply').show(); // Show the "Send Reply" button
            $('#adminReplyDisplay').hide(); // Hide admin reply display
            $('#additionalFieldsContainer').hide();
        } else if (showForm && topic === 'Registration') {
            $('#adminReplyDisplay').hide(); // Hide admin reply display
            $('#additionalFieldsContainer').show(); 
        } else {
            $('#replyContainer').hide();
            $('#submitReply').hide(); // Hide the "Send Reply" button
            $('#adminReplyDisplay').show(); // Show admin reply display
            $('#adminReplyText').text(adminReply); // Set the admin reply text
            $('#additionalFieldsContainer').hide();
        }
    });

    // Handle the 'Add to JSON' button click event
    $('#addToJson').on('click', function() {
        var email = $('#email').val();
        var university = $('#university').val();
        var city = $('#city').val();

        // Example function to handle the collected data
        console.log({
            email: email,
            university: university,
            city: city
        });

        // Optionally, you can send this data to your server or process it further
    });

    // Handle deletion of requests
    $('#deleteRequest').on('click', function() {
        var requestId = $('input[name="requestId"]').val();
        window.location.href = '/UniRent/Admin/deleteSupportRequest/' + requestId;
    });

    $('#reportModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget); // Button that triggered the modal
        const content = button.data('content');
        const subject = button.data('subject');
        const type = button.data('type');
        const banDate = button.data('bandate');
        const showDelete = banDate === null; // Show form only if status is 0
        const showReview = type === 'review';
        const reportId = button.data('id');
        const made = button.data('made');
        const reviewId = button.data('reviewid');

        

        // Set modal content
        $('#reportContent').text(content);
        $('#reportSubject').html(`<a href="/UniRent/Admin/profile/${subject}">${subject}</a>`);
        $('#reportType').text(type);
        $('input[name="reportId"]').val(reportId);
        $('#reportMade').text(new Date(made).toLocaleDateString());
        if (banDate === null) {
            $('#reportBanDate').text('Not banned');
            if (showReview) {
                $('#banReview').show();
            } else {
                $('#banReview').hide();
            }
        } else {
            $('#reportBanDate').text(new Date(banDate).toLocaleDateString());
        }


        // Show or hide the reply form based on the request status
        if (showDelete) {
            $('#deleteReport').show();
        } else {
            $('#deleteReport').hide();
        }
        if (showReview) {
                var title = button.data('title');
                var description = button.data('description');
                reviewContainer = document.getElementById('reportedReview');
                reviewContainer.innerHTML = `
                    <h6>${title}</h6>
                    <p>${description}</p>
                `;
                
            $('#reviewContainer').show();
            $('#reportedReview').show();
            $('input[name="reviewId"]').val(reviewId);
        } else {
            $('#reviewContainer').hide();
        }
    });
    $('#deleteReport').on('click', function() {
        var reportId = $('input[name="reportId"]').val();
        window.location.href = '/UniRent/Admin/deleteReport/' + reportId;
    });
    $('#banReview').on('click', function() {
        var reviewId = $('input[name="reviewId"]').val();
        var reportId = $('input[name="reportId"]').val();
        window.location.href = '/UniRent/Admin/ban/' + reviewId +'/review/'+reportId;
    });
    // Populate the request list from JSON data
    function populateRequestList(data) {
        var container = document.querySelector('.request-list');
        container.innerHTML = '';

        data.forEach(function(item) {
            var requestItem = document.createElement('div');
            requestItem.className = 'request-item';
            requestItem.setAttribute('data-toggle', 'modal');
            requestItem.setAttribute('data-target', '#requestModal');
            requestItem.setAttribute('data-content', item.message);
            requestItem.setAttribute('data-author', item.author);
            requestItem.setAttribute('data-topic', item.topic);
            requestItem.setAttribute('data-id', item.id);
            requestItem.setAttribute('data-status', item.status);
            requestItem.setAttribute('data-reply', item.reply);

            if (item.status === 0) {
                requestItem.classList.add('font-weight-bold');
            }
            requestItem.innerHTML = `
                <div class="text-truncate">${item.message}</div>
                <div class="smallMessages text-gray-500">${item.author} · ${item.topic}</div>
            `;
            container.appendChild(requestItem);
        });
    }
    function populateReportList(data) {
        var container = document.querySelector('.report-list');
        container.innerHTML = '';

        data.forEach(function(report) {
            var reportItem = document.createElement('div');
            reportItem.className = 'report-item';
            reportItem.setAttribute('data-toggle', 'modal');
            reportItem.setAttribute('data-target', '#reportModal');
            reportItem.setAttribute('data-content', report.description);
            reportItem.setAttribute('data-subject', report.usernameSubject);
            reportItem.setAttribute('data-type', report.type);
            reportItem.setAttribute('data-id', report.id);
            reportItem.setAttribute('data-bandate', report.banDate);
            reportItem.setAttribute('data-made', report.made);
            if (report.review !== null) {
            reportItem.setAttribute('data-reviewid', report.review.id);
            reportItem.setAttribute('data-title', report.review.title);
            reportItem.setAttribute('data-description', report.review.description);
            } else {
                reportItem.setAttribute('data-reviewid', null);
            }

            reportItem.innerHTML = `
                 <div class="smallMessages text-gray-500">${new Date(report.made).toLocaleDateString()}</div>
                <span class="${report.banDate === null ? 'font-weight-bold' : ''}">${report.description}</span>
            `;
            container.appendChild(reportItem);
        });
    }
    
    if (document.querySelector('.nextBtn') && document.querySelector('.prevBtn') && typeof jsonData !== 'undefined') {


    var nextBtn = document.querySelector('.nextBtn');
    var prevBtn = document.querySelector('.prevBtn');
    nextBtn.addEventListener('click', function() {
        var currentPage = parseInt(document.querySelector('.activeBtn').textContent);
        if (jsonData[currentPage]) {
            populateRequestList(jsonData[currentPage]);
        }
    });
    prevBtn.addEventListener('click', function() {
        var currentPage = parseInt(document.querySelector('.activeBtn').textContent);
        if (jsonData[currentPage]) {
            populateRequestList(jsonData[currentPage]);
        }
    });
    // Initialize with the content of the first page
    var firstPage = Object.keys(jsonData)[0];
    if (firstPage) {
        populateRequestList(jsonData[firstPage]);
    }
} else if (typeof reportsData !== 'undefined' && document.querySelector('.nextBtn') && document.querySelector('.prevBtn')) {
    var nextBtn = document.querySelector('.nextBtn');
    var prevBtn = document.querySelector('.prevBtn');
    nextBtn.addEventListener('click', function() {
        var currentPage = parseInt(document.querySelector('.activeBtn').textContent);
        if (reportsData[currentPage]) {
            populateReportList(reportsData[currentPage]);
        }
    });
    prevBtn.addEventListener('click', function() {
        var currentPage = parseInt(document.querySelector('.activeBtn').textContent);
        if (reportsData[currentPage]) {
            populateReportList(reportsData[currentPage]);
        }
    });
    // Initialize with the content of the first page
    var firstPage = Object.keys(reportsData)[0];
    if (firstPage) {
        populateReportList(reportsData[firstPage]);
    }
}

if (modalMessage !== '') {
    $('#confirmationModal').modal('show');
}
var closeConfirm = document.getElementById('closeConfirm');
var confirmClose = document.getElementById('confirmClose');
closeConfirm.addEventListener('click', function() {
    window.location.href = currentPage;
});
confirmClose.addEventListener('click', function() {
    window.location.href = currentPage;
});
    
});
