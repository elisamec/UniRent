document.addEventListener('DOMContentLoaded', function () {
    const messagesDropdown = document.getElementById('messagesDropdown');
    const messageCount = document.getElementById('messageCount');
    const messageList = document.getElementById('messageList');
    
    let contentLoaded = false;

    messagesDropdown.addEventListener('click', function () {
        if (!contentLoaded) {
            fetch('/UniRent/SupportRequest/getSupportReply')
                .then(response => response.json())
                .then(data => {
                    // Update the message count if greater than 0
                    if (data.countReply > 0) {
                        messageCount.textContent = data.countReply;
                        messageCount.style.display = 'inline'; // Show count
                    } else {
                        messageCount.textContent = ''; // Hide count
                        messageCount.style.display = 'none'; // Hide count
                    }
                    
                    // Build the list items
                    const replies = data.replies;
                    const limit = Math.min(replies.length, 4); // Show up to 4 replies
                    messageList.innerHTML = ''; // Clear previous messages
                    for (let i = 0; i < limit; i++) {
                        const reply = replies[i];
                        const isUnread = reply.statusRead === 0 || reply.statusRead === false;
                        const itemClass = isUnread ? 'font-weight-bold requestItem' : 'requestItem';
                        
                        const listItem = document.createElement('a');
                        listItem.className = 'dropdown-item d-flex align-items-center';
                        listItem.href = '#';
                        listItem.innerHTML = `
                            <div class="${itemClass}" data-request="${reply.message}" data-reply="${reply.supportReply}" data-topic="${reply.topic}" data-id="${reply.id}">
                                <div class="text-truncate">${reply.supportReply}</div>
                                <div class="smallMessages text-gray-500">${reply.topic}</div>
                            </div>
                        `;
                        messageList.appendChild(listItem);
                    }
                    
                    contentLoaded = true;
                })
                .catch(error => console.error('Error fetching support replies:', error));
        }
    });

    // Hide message count when the dropdown is closed
    document.addEventListener('click', function(event) {
        if (!messagesDropdown.contains(event.target) && !messageList.contains(event.target)) {
            messageCount.style.display = 'none'; // Hide count
        }
    });

    // Modal handling
    const replyModal = document.getElementById('replyModal'); // Ensure this ID matches your modal
    const closeReply = document.getElementById('closeReply');
    const requestContent = document.getElementById('requestContent');
    const replyContent = document.getElementById('replyContent');
    const requestTopic = document.getElementById('requestTopic');
    const replyClose = document.getElementById('replyClose');
    
    // Show modal when a requestItem is clicked
    document.addEventListener('click', function(event) {
        if (event.target.closest('.requestItem')) {
            const target = event.target.closest('.requestItem');
            replyModal.style.display = 'block';
            
            // Set modal content
            requestContent.textContent = target.dataset.request;
            replyContent.textContent = target.dataset.reply;
            requestTopic.textContent = target.dataset.topic;

            // Handle closing of the modal
            closeReply.addEventListener('click', function() {
                window.location.href = '/UniRent/SupportRequest/readSupportReply/' + target.dataset.id;
            });
            replyClose.addEventListener('click', function() {
                window.location.href = '/UniRent/SupportRequest/readSupportReply/' + target.dataset.id;
            });
        }
    });

    // Close modal if clicking outside of it
    document.addEventListener('click', function(event) {
        if (replyModal && !replyModal.contains(event.target) && !event.target.closest('.requestItem')) {
            replyModal.style.display = 'none';
        }
    });
    
    function populateReplyList(data) {
        var container = document.querySelector('.reply-list');
        container.innerHTML = '';
        if (data.length === 0) {
            var noReplies = document.createElement('p');
            noReplies.innerHTML = 'No replies found.';
            container.appendChild(noReplies);
            return;
        }
        var anchor = document.createElement('a');
        anchor.className = 'dropdown-item d-flex align-items-center';
        anchor.href = '#';
        data.forEach(function(item) {
            var requestItem = document.createElement('div');
            requestItem.className = 'requestItem';
            requestItem.setAttribute('data-request', item.message);
            requestItem.setAttribute('data-topic', item.topic);
            requestItem.setAttribute('data-id', item.id);
            requestItem.setAttribute('data-reply', item.supportReply);

            if (item.statusRead === 0 || item.statusRead === false) {
                requestItem.classList.add('font-weight-bold');
            }
            requestItem.innerHTML = `
                <div class="text-truncate">${item.supportReply}</div>
                <div class="smallMessages text-gray-500"> ${item.topic}</div>
            `;
            anchor.appendChild(requestItem);
        });
        container.appendChild(anchor);
    }
    if (document.querySelector('.nextBtn') && document.querySelector('.prevBtn') && typeof supportReplies !== 'undefined') {
        if (supportReplies.length === 0) {
            populateReplyList([]);
            return;
        }

        var nextBtn = document.querySelector('.nextBtn');
        var prevBtn = document.querySelector('.prevBtn');
        nextBtn.addEventListener('click', function() {
            var currentPageNumber = parseInt(document.querySelector('.activeBtn').textContent);
            if (supportReplies[currentPageNumber]) {
                populateReplyList(supportReplies[currentPageNumber]);
            }
        });
        prevBtn.addEventListener('click', function() {
            var currentPageNumber = parseInt(document.querySelector('.activeBtn').textContent);
            if (supportReplies[currentPageNumber]) {
                populateReplyList(supportReplies[currentPageNumber]);
            }
        });
        // Initialize with the content of the first page
        var firstPage = Object.keys(supportReplies)[0];
        if (firstPage) {
            populateReplyList(supportReplies[firstPage]);
        }
    }
    
});